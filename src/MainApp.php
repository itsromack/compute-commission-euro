<?php

namespace App;

use App\Services\CardMetaDataService;
use App\Services\ExchangeRateService;
use App\Utils\InputParser;
use App\Utils\CountryCheck;
use App\Utils\InputFileReader;
use App\Utils\NumbersUtil;

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
                $currency_code = $obj->getCurrency();
                $rate = $this->rates->getRateByCurrency($currency_code);
                // Compute commission
                $commission = NumbersUtil::commission_compute(
                    $rate,
                    $obj->getAmount(),
                    $currency_code,
                    CountryCheck::isEU($country_code)
                );
                $this->output($commission);
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

    public function output($str)
    {
        echo $str;
        echo "\n";
    }
}