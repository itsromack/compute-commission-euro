<?php

namespace App\Data;

use App\Utils\NumbersUtil;

class EURate
{
    protected $rates;
    protected $base;
    protected $date;

    public function __construct(\stdClass $data)
    {
        $this->rates = $data->rates;
        $this->base = $data->base;
        $this->date = $data->date;
    }

    public function getRates()
    {
        return $this->rates;
    }

    public function getBase()
    {
        return $this->base;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getRateByCurrency($currency_code)
    {
        if ($currency_code === 'EUR') return 1;

        $rates = (array) $this->rates;
        return $rates[$currency_code];
    }

    public function compute($amount, $currency_code, $is_eu = false)
    {
        $rate = $this->getRateByCurrency($currency_code);
        if ($currency_code == EURO_CURRENCY or $rate == 0) {
            $rated_amount = $amount;
        } else {
            $rated_amount = $amount / $rate;
        }

        $result = $rated_amount * RATE_MULTIPLIER_NON_EU;
        if ($is_eu) {
            $result = $rated_amount * RATE_MULTIPLIER_EU;
        }
        return NumbersUtil::round_ceil($result);
    }
}