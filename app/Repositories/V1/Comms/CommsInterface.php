<?php

namespace App\Repositories\V1\Comms;
use App\Repositories\V1\Core\CoreInterface;

interface CommsInterface  extends CoreInterface {

    /**
     * Generate OTP.
     * @ $identifier: The identity that will be tied to the OTP.
     * @ $type: The type of token to be generated, supported types are numeric and alpha_numeric.
     * @ $length (optional | default = 4): The length of token to be generated.
     * @ $validity (optional | default = 10): The validity period of the OTP in minutes.
     */
    public function generateOtp(string $identifier, string $type, int $length = 4, int $validity = 1);
    public function validateOtp(string $identifier, string $token);
    public function getOtpRecord(string $identifier, string $token);
}
