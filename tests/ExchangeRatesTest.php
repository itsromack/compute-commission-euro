<?php

use PHPUnit\Framework\TestCase;

use Exception;
use App\Utils\CountryCheck;
use App\Services\CardMetaDataService;
use App\Services\ExchangeRateService;
use App\Utils\InputParser;
use App\Utils\InputFileReader;
use App\Utils\NumbersUtil;

final class ExchangeRatesTest extends TestCase
{
    public function testIsEUCountry()
    {
        $this->assertEquals(true, CountryCheck::isEU('AT'));
        $this->assertEquals(true, CountryCheck::isEU('BE'));
        $this->assertEquals(true, CountryCheck::isEU('es'));
        $this->assertEquals(true, CountryCheck::isEU('Hu'));
        $this->assertEquals(true, CountryCheck::isEU(' lV'));
        $this->assertEquals(true, CountryCheck::isEU(' Ro '));
        $this->assertEquals(true, CountryCheck::isEU('SI'));
        $this->assertEquals(true, CountryCheck::isEU('SK'));
        $this->assertEquals(false, CountryCheck::isEU('EEE'));
        $this->assertEquals(false, CountryCheck::isEU('SSS'));
    }

    public function testCardMetaDataService()
    {
        $bins = [45717360, 516793, 45417360, 41417360, 4745030];
        foreach ($bins as $bin) {
            $service = new CardMetaDataService;
            $service->setBin($bin);
            try {
                $service->send();
                $objResult = $service->getData();
                $this->assertEquals(true, is_object($objResult));
                $this->assertEquals(true, property_exists($objResult, 'number'));
                $this->assertEquals(true, property_exists($objResult, 'scheme'));
                $this->assertEquals(true, property_exists($objResult, 'country'));
                $this->assertEquals(true, property_exists($objResult, 'bank'));
                $this->assertEquals(false, property_exists($objResult, 'bankrupt'));
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }
    }

    public function testExchangeRateService()
    {
        $service = new ExchangeRateService;
        try {
            $service->send();
            $result = $service->getData();
            $this->assertEquals(true, is_object($result));
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }

    public function testInputParser()
    {
        $parser = new InputParser;
        $parser->parse('{"bin":"45717360","amount":"100.00","currency":"EUR"}');
        $objResult = $parser->getData();

        $this->assertEquals(true, is_object($objResult));
        $this->assertEquals(true, property_exists($objResult, 'bin'));
        $this->assertEquals(true, property_exists($objResult, 'amount'));
        $this->assertEquals(true, property_exists($objResult, 'currency'));
        $this->assertEquals(false, property_exists($objResult, 'currencies'));
    }

    public function testInputFileReader()
    {
        $file_path = "input.txt";
        $fileReader = new InputFileReader;
        $fileReader->setFile($file_path);
        $fileReader->read();
        $this->assertEquals(true, is_array($fileReader->getContent()));
    }

    public function testRoundCeil()
    {
        $this->assertEquals(10.05, NumbersUtil::round_ceil(10.04123));
        $this->assertEquals(0.09, NumbersUtil::round_ceil(0.08123));
        $this->assertEquals(3.15, NumbersUtil::round_ceil(3.1416));
        $this->assertEquals(false, (NumbersUtil::round_ceil(3.1416) == 3.14));
    }
}