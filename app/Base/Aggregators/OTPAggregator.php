<?php

namespace App\Base\Aggregators;

class OTPAggregator
{
    static public function generateOTP(
        int $otp_length = 4,
        bool $numbers_except_0 = true,
        bool $include_0 = true,
        bool $include_lc_chars = false,
        bool $include_uc_chars = false
    ) {

        if (
            $numbers_except_0 == false &&
            $include_0 == false &&
            $include_lc_chars == false &&
            $include_uc_chars == false
        )
            return false;

        $available_chars = "";
        if ($include_0)
            $available_chars .= "0";
        if ($numbers_except_0)
            $available_chars .= "123456789";
        if ($include_lc_chars)
            $available_chars .= "abcdefghijklmnopqrstuvwxyz";
        if ($include_uc_chars)
            $available_chars .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

        $otp = "";
        for ($i = 1; $i <= $otp_length; $i++) {
            $otp .= substr($available_chars, (rand() % (strlen($available_chars))), 1);
        }

        return $otp;
    }
}
