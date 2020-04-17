<?php

namespace App\Utils;

use App\Data\InputData;

class InputParser
{
    private $jsonData;
    private $parsedJsonData;

    public function parse($json)
    {
        $this->jsonData = $json;
        $this->parsedJsonData = json_decode($this->jsonData);
    }

    public function getJsonData()
    {
        return $this->jsonData;
    }

    public function getData()
    {
        return new InputData($this->parsedJsonData);
    }
}