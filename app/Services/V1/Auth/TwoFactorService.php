<?php

namespace App\Services\V1\Auth;



use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;


use App\Repositories\V1\Comms\CommsInterface;


class TwoFactorService
{
    private $otpLength = 6;
    private $otpValidaty = 5;

    private $comms;

    // 5 minutes OTP expiry
    private int $ttl = 300;

    // Max wrong attempts before lockout
    private int $maxAttempts = 5;

    public function __construct(CommsInterface $comms)
    {
        $this->comms = $comms;
    }

    private function key(string $token): string
    {
        return "2fa:pending:{$token}";
    }

    /**
     * Start OTP flow for signup
     * - Store raw signup data in cache until verified
     */
    public function startSignup(array $signupData, string $ip, ?string $ua): array
    {
        $pendingToken = bin2hex(random_bytes(32));


        $otpCreate = $this->createOtp($signupData['email']);
        // $otp = $this->generateOtp();
        $otp = $otpCreate->token;
        $otpHash = $this->hashOtp($otp);

        $payload = [
            'type'        => 'signup',
            'signup_data' => $signupData,
            'otp_hash'    => $otpHash,
            'attempts'    => 0,
            'ip'          => $ip,
            'ua'          => (string)$ua,
            'issued_at'   => now()->unix(),
        ];

        Cache::put($this->key($pendingToken), $payload, $this->ttl);



        // notify()
        //     ->on('otp_sent')
        //     ->with('caspertalks@yopmail.com', ['otp' => $otp, 'name' => $signupData['name']])
        //     ->via('mail')
        //     ->send(\App\Notifications\UserInvitationNotification::class);

        $this->sendOtpEmail($signupData, $otp);


        Log::info("Signup OTP: {$otp} sent to {$signupData['email']}");

        return [$pendingToken, $this->ttl];
    }

    /**
     * Start OTP flow for login
     * - User must exist & credentials validated first
     */
    public function startLogin(User $user, string $ip, ?string $ua): array
    {
        $pendingToken = bin2hex(random_bytes(32));

        $otpCreate = $this->createOtp($user->email);
        // $otp = $this->generateOtp();
        $otp = $otpCreate->token;
        $otpHash = $this->hashOtp($otp);

        $payload = [
            'type'      => 'login',
            'user_id'   => $user->id,
            'otp_hash'  => $otpHash,
            'attempts'  => 0,
            'ip'        => $ip,
            'ua'        => (string)$ua,
            'issued_at' => now()->unix(),
        ];

        Cache::put($this->key($pendingToken), $payload, $this->ttl);

        $this->sendOtpEmail($user, $otp);

        Log::info("Login OTP: {$otp} sent to {$user->email}");

        return [$pendingToken, $this->ttl];
    }

    /**
     * Verify OTP (used for both signup & login)
     */
    public function verify(string $pendingToken, string $otp, string $email, string $ip, ?string $ua): object
    {
        $cacheKey = $this->key($pendingToken);
        $data = Cache::get($cacheKey);
        if (!$data) {
            return (object)['ok' => false, 'status' => 400, 'message' => 'Invalid or expired token.'];
        }

        if ($data['ip'] !== $ip || $data['ua'] !== (string)$ua) {
            return (object)['ok' => false, 'status' => 403, 'message' => 'Context mismatch.'];
        }

        // Brute force protection
        if ($data['attempts'] >= $this->maxAttempts) {
            Cache::forget($cacheKey);
            return (object)['ok' => false, 'status' => 429, 'message' => 'Too many attempts.'];
        }

        // Check OTP
        // $valid = hash_equals($data['otp_hash'], $this->hashOtp($otp));
        // if (!$valid) {
        //     $data['attempts']++;
        //     Cache::put($cacheKey, $data, $this->ttlRemaining($cacheKey));
        //     return (object)['ok' => false, 'status' => 401, 'message' => 'Invalid OTP.'];
        // }

        $valid = $this->comms->validateOtp($email,$otp);
        if(!$valid->status){
            return (object)['ok' => false, 'status' => 401, 'message' => $valid->message];
        }


        Cache::forget($cacheKey);

        // Handle flow types
        if ($data['type'] === 'signup') {
            return (object)['ok' => true, 'status' => 201, 'flow' => 'signup', 'suUser' => $data];
        }

        if ($data['type'] === 'login') {
            $user = User::find($data['user_id']);
            if (!$user) {
                return (object)['ok' => false, 'status' => 400, 'message' => 'Invalid session.'];
            }

            return (object)['ok' => true, 'status' => 200, 'flow' => 'login', 'user' => $user];
        }

        return (object)['ok' => false, 'status' => 400, 'message' => 'Unknown flow.'];
    }

    public function verifyEmailData(string $pendingToken): object
    {
        $cacheKey = $this->key($pendingToken);
        $data = Cache::get($cacheKey);

        if (!$data) {
            return (object)['ok' => false, 'status' => 400, 'message' => 'Invalid or expired token.'];
        }

        if ($data['type'] === 'signup') {
            return (object)['ok' => true, 'flow' => 'signup'];
        }

        if ($data['type'] === 'login') {
            return (object)['ok' => true, 'flow' => 'login'];
        }

        return (object)['ok' => false, 'flow' => 'unknown'];
    }

    /**
     * Resend OTP
     */
    public function resend(string $pendingToken, string $ip, ?string $ua): object
    {
        $cacheKey = $this->key($pendingToken);
        $data = Cache::get($cacheKey);

        if (!$data) {
            return (object)['ok' => false, 'status' => 400, 'message' => 'Invalid or expired token.'];
        }

        if ($data['ip'] !== $ip || $data['ua'] !== (string)$ua) {
            return (object)['ok' => false, 'status' => 403, 'message' => 'Context mismatch.'];
        }

        // Rate limit resend
        $rlKey = "otp-resend:{$pendingToken}:{$ip}";
        if (RateLimiter::tooManyAttempts($rlKey, 3)) {
            return (object)['ok' => false, 'status' => 429, 'message' => 'Resend limit reached.'];
        }
        RateLimiter::hit($rlKey, 30);

        $otp = $this->generateOtp();
        $data['otp_hash'] = $this->hashOtp($otp);

        Cache::put($cacheKey, $data, $this->ttlRemaining($cacheKey));

        // Send OTP again
        if ($data['type'] === 'signup') {
            Log::info("Resent signup OTP: {$otp} to {$data['signup_data']['email']}");
        } elseif ($data['type'] === 'login') {
            $user = User::find($data['user_id']);
            if ($user) {
                Log::info("Resent login OTP: {$otp} to {$user->email}");
            }
        }

        return (object)['ok' => true, 'status' => 200, 'expiresIn' => $this->ttlRemaining($cacheKey)];
    }

    public function sendOtpEmail($with, $otp)
    {
        //Laravel Default Email Template
        // notify()
        //     ->on('otp_sent')
        //     ->with($user, ['otp' => $otp, 'name' => $user->name])
        //     ->via('mail')
        //     ->send();



        if (gettype($with) == 'object') {
            notify()
                ->on('otp_sent')
                ->with($with, ['otp' => $otp, 'name' => $with->name])
                ->via('mail')
                ->send(\App\Notifications\UserInvitationNotification::class);
        }

        if (gettype($with) == 'array') {

            notify()
                ->on('otp_sent')
                ->with($with['email'], ['otp' => $otp, 'name' => $with['name']])
                ->via('mail')
                ->send(\App\Notifications\UserInvitationNotification::class);
        }
    }

    /**
     * Generate a 6-digit OTP
     */
    private function generateOtp(): string
    {
        return str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Hash OTP with APP_KEY
     */
    private function hashOtp(string $otp): string
    {
        return hash('sha256', $otp . env('APP_KEY'));
    }

    /**
     * TTL remaining helper
     */
    private function ttlRemaining(string $cacheKey): int
    {
        return $this->ttl; // Could be improved if using Redis with TTL fetch
    }

    private function createOtp(string $email)
    {
        return $this->comms->generateOtp($email, 'numeric', $this->otpLength, $this->otpValidaty);
    }
}
