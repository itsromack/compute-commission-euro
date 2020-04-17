<?php

use PHPUnit\Framework\TestCase;

use App\Utils\CountryCheck;
use App\Services\CardMetaDataService;
use App\Services\ExchangeRateService;
use App\Utils\InputParser;
use App\Utils\InputFileReader;

final class ExchangeRatesTest extends TestCase
{
    public function testCanBeX()
    {
        $this->assertEquals(true, true);
    }

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
            $service->send();
            $objResult = $service->getData();
            $this->assertEquals(true, is_object($objResult));
            $this->assertEquals(true, property_exists($objResult, 'number'));
            $this->assertEquals(true, property_exists($objResult, 'scheme'));
            $this->assertEquals(true, property_exists($objResult, 'country'));
            $this->assertEquals(true, property_exists($objResult, 'bank'));
            $this->assertEquals(false, property_exists($objResult, 'bankrupt'));
        }
    }

    public function testExchangeRateService()
    {
        $service = new ExchangeRateService;
        $service->send();
        $result = $service->getData();
        $this->assertEquals(true, is_object($result));
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
}