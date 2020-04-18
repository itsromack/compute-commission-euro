<?php

namespace App\Utils;

define('EURO_CURRENCY', 'EUR');
define('RATE_MULTIPLIER_EU', 0.01);
define('RATE_MULTIPLIER_NON_EU', 0.02);

class NumbersUtil
{
    public static function round_ceil($number, $precision = 2)
    {
        $fig = pow(10, $precision);
        return ceil($number * $fig) / $fig;
    }

    public static function commission_compute(
        $rate,
        $amount,
        $currency_code,
        $is_eu = false
    )
    {
        if ($currency_code == EURO_CURRENCY or $rate == 0) {
            $rated_amount = $amount;
        } else {
            $rated_amount = $amount / $rate;
        }

        $result = $rated_amount * RATE_MULTIPLIER_NON_EU;
        if ($is_eu) {
            $result = $rated_amount * RATE_MULTIPLIER_EU;
        }
        return self::round_ceil($result);
    }
}