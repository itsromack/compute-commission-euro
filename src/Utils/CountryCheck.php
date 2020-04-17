<?php

namespace App\Utils;

define('EU_COUNTRIES', [
        'AT',
        'BE',
        'BG',
        'CY',
        'CZ',
        'DE',
        'DK',
        'EE',
        'ES',
        'FI',
        'FR',
        'GR',
        'HR',
        'HU',
        'IE',
        'IT',
        'LT',
        'LU',
        'LV',
        'MT',
        'NL',
        'PO',
        'PT',
        'RO',
        'SE',
        'SI',
        'SK'
    ]);

class CountryCheck
{
    public static function isEU($country_code)
    {
        $country_code = strtoupper(trim($country_code));
        if (in_array($country_code, EU_COUNTRIES)) {
            return true;
        }

        return false;
    }
}