<?php

namespace App\Data;

class InputData
{
    protected $bin;
    protected $amount;
    protected $currency;

    public function __construct(\stdClass $data)
    {
        $this->bin = $data->bin;
        $this->amount = $data->amount;
        $this->currency = $data->currency;
    }

    public function getBin()
    {
        return $this->bin;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getCurrency()
    {
        return $this->currency;
    }
}