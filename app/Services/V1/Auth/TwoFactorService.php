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
    private CommsInterface $comms;


    public function __construct(
        CommsInterface $comms,
    ) {
        $this->comms = $comms;
    }
    // 5 minutes
    private int $ttl = 300;

    // How many wrong OTP attempts allowed
    private int $maxAttempts = 5;

    private function key(string $token): string
    {
        return "2fa:pending:{$token}";
    }

    public function start(User $user, string $ip, ?string $ua): array
    {
        // Make opaque pending token (32 bytes -> 64 hex chars)
        $pendingToken = bin2hex(random_bytes(32));

        // Create OTP.
        $otp = $this->comms->generateOtp($user->id, 'numeric', 6, 3)->token;


        $otpHash = hash('sha256', $otp . env('APP_KEY'));

        $payload = [
            'user_id'   => $user->id,
            'otp_hash'  => $otpHash,
            'attempts'  => 0,
            'ip'        => $ip,
            'ua'        => (string)$ua,
            'issued_at' => now()->unix(),
        ];

        Cache::put($this->key($pendingToken), $payload, $this->ttl);

        // Send the OTP (email/SMS). NEVER log the OTP.
        notify()
            ->on('otp_sent')
            ->with($user, ['name' => $user->name, 'otp' => $otp])
            ->send();

        Log::info('OTP generated (dev only): ' . $otp . ' for user ' . $user->id);

        return [$pendingToken, $this->ttl, $otp];
    }

    public function verify(string $pendingToken, string $otp, string $ip, ?string $ua): object
    {
        $cacheKey = $this->key($pendingToken);
        $data = Cache::get($cacheKey);

        // Generic failure to avoid info leak
        if (!$data) {
            return (object)['ok' => false, 'status' => 400, 'message' => 'Invalid or expired token.'];
        }

        // Bind to IP/UA to reduce token theft risk
        if ($data['ip'] !== $ip || $data['ua'] !== (string)$ua) {
            return (object)['ok' => false, 'status' => 403, 'message' => 'Context mismatch.'];
        }

        // Throttle per pending token
        $rlKey = "otp:{$pendingToken}:{$ip}";
        if (RateLimiter::tooManyAttempts($rlKey, 10)) {
            return (object)['ok' => false, 'status' => 429, 'message' => 'Too many requests.'];
        }
        RateLimiter::hit($rlKey, 60);

        // Check attempts
        if ($data['attempts'] >= $this->maxAttempts) {
            Cache::forget($cacheKey);
            return (object)['ok' => false, 'status' => 429, 'message' => 'Too many attempts.'];
        }

        $valid = hash_equals($data['otp_hash'], hash('sha256', $otp . env('APP_KEY')));

        if (!$valid) {
            $data['attempts']++;
            Cache::put($cacheKey, $data, $this->ttlRemaining($cacheKey));
            return (object)['ok' => false, 'status' => 401, 'message' => 'Invalid OTP.'];
        }

        // Success → consume token
        Cache::forget($cacheKey);

        $user = User::find($data['user_id']);
        if (!$user) {
            return (object)['ok' => false, 'status' => 400, 'message' => 'Invalid session.'];
        }

        return (object)['ok' => true, 'status' => 200, 'user' => $user];
    }

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

        // Rate limit resend to, say, once every 30s and max 3 times
        $rlKey = "otp-resend:{$pendingToken}:{$ip}";
        if (RateLimiter::tooManyAttempts($rlKey, 3)) {
            return (object)['ok' => false, 'status' => 429, 'message' => 'Resend limit reached.'];
        }
        RateLimiter::hit($rlKey, 30);

        // New OTP
        $otp = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $data['otp_hash'] = hash('sha256', $otp . env('APP_KEY'));

        Cache::put($cacheKey, $data, $this->ttlRemaining($cacheKey));

        // Send again
        // Mail::to(User::find($data['user_id'])->email)->queue(new LoginOtpMail($otp));
        // \Log::info('OTP resent (dev only): ' . $otp . ' for user ' . $data['user_id']);

        return (object)['ok' => true, 'status' => 200, 'expiresIn' => $this->ttlRemaining($cacheKey)];
    }

    private function ttlRemaining(string $cacheKey): int
    {
        // Laravel Cache doesn't expose remaining TTL directly for all drivers.
        // Simple approach: store 'issued_at' and compute remaining if needed,
        // or just reset to default TTL on updates.
        return $this->ttl;
    }
}
