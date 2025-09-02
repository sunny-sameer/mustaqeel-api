<?php

namespace App\Repositories\V1\Comms;


use App\Helper\Helper;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;


// Core Repository
use App\Repositories\V1\Core\CoreRepository;


use Ichtrojan\Otp\Otp;
use Ichtrojan\Otp\Models\Otp as OtpModel;

use App\Models\User;

class CommsRepository extends CoreRepository implements CommsInterface
{

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function generateOtp(string $identifier, string $type, int $length = 4, int $validity = 1)
    {
        return (new Otp)->generate($identifier, $type, $length, $validity);
    }

    public function validateOtp(string $identifier, string $token)
    {
        $data = (new Otp)->validate($identifier, $token);
        $data->message = __($data->message);

        return $data;
    }

    public function getOtpRecord(string $identifier, string $token)
    {
        return OtpModel::where('identifier', $identifier)->where('token', $token)->first();
    }
}
