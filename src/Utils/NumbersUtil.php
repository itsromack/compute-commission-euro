<?php

namespace App\Utils;

class NumbersUtil
{
    public static function round_ceil($number, $precision = 2)
    {
        $fig = pow(10, $precision);
        return ceil($number * $fig) / $fig;
    }
}