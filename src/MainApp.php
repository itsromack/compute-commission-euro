<?php

namespace App;

use App\Services\CardMetaDataService;
use App\Services\ExchangeRateService;
use App\Utils\InputParser;
use App\Utils\CountryCheck;
use App\Utils\InputFileReader;

class MainApp
{
    protected $input_file;
    protected $reader;
    protected $parser;
    protected $cardService;
    protected $rateService;
    protected $rates;

    public function __construct()
    {
        $this->reader = new InputFileReader;
        $this->parser = new InputParser;
        $this->cardService = new CardMetaDataService;
        $this->rateService = new ExchangeRateService;
        $this->rateService->send();
        $this->rates = $this->rateService->getData();
    }

    public function setFile($file)
    {
        $this->input_file = $file;
    }

    public function run()
    {
        $this->reader->setFile($this->input_file);
        $this->reader->read();
        $file_content = $this->reader->getContent();

        if (!empty($file_content)) {
            foreach($file_content as $data) {
                $this->parser->parse($data);
                $obj = $this->parser->getData();
                $metadata = $this->fetchMetaData($obj);
                // Skip current data if invalid
                if ($metadata === false) continue;
                $country_code = $metadata->getCountryCode();
                echo $this->rates->compute(
                    $obj->getAmount(),
                    $obj->getCurrency(),
                    CountryCheck::isEU($country_code)
                ) . "\n";
            }
        }
    }

    public function fetchMetaData($obj)
    {
        $this->cardService->setBin($obj->getBin());
        $this->cardService->send();
        $metadata = $this->cardService->getData();
        if (!is_null($metadata)) {
            return $metadata;
        }
        return false;
    }
}