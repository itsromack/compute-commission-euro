<?php

namespace App\Data;

class CardMetaData
{
    protected $number;
    protected $scheme;
    protected $type;
    protected $brand;
    protected $prepaid;
    protected $country;
    protected $bank;

    public function __construct(\stdClass $data)
    {
        $this->number = $data->number;
        $this->scheme = $data->scheme;
        $this->type = (property_exists($data, 'type')) ? $data->type : null;
        $this->brand = (property_exists($data, 'brand')) ? $data->brand : null;
        $this->prepaid = (property_exists($data, 'prepaid')) ? $data->prepaid : null;
        $this->country = $data->country;
        $this->bank = $data->bank;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function getScheme()
    {
        return $this->scheme;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getBrand()
    {
        return $this->brand;
    }

    public function getPrepaid()
    {
        return $this->prepaid;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function getCountryCode()
    {
        if (property_exists($this->getCountry(), 'alpha2')) {
            return $this->getCountry()->alpha2;
        }
        return null;
    }

    public function getCurrency()
    {
        if (property_exists($this->getCountry(), 'currency')) {
            return $this->getCountry()->currency;
        }
        return null;
    }

    public function getBank()
    {
        return $this->bank;
    }
}