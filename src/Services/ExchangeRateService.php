<?php

namespace App\Services;

define('EXCHANGE_RATE_URL', 'https://api.exchangeratesapi.io/latest');

use App\Data\EURate;

class ExchangeRateService
{
    protected $result;

    public function send()
    {
        try {
            $result = file_get_contents(EXCHANGE_RATE_URL);

            $this->result = json_decode($result);

        } catch (\Exception $e) {
            error_log($e->getMessage());
        }

        return null;
    }

    public function getData()
    {
        if (!is_null($this->result)) {
            return new EURate($this->result);
        }
        return null;
    }
}