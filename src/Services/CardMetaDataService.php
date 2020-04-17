<?php

namespace App\Services;

define('BIN_LOOKUP_URL', 'https://lookup.binlist.net/');

use App\Data\CardMetaData;

class CardMetaDataService
{
    private $bankIdentificationNumber;
    protected $result;

    public function setBin($bin)
    {
        $this->bankIdentificationNumber = $bin;
    }

    public function getBinURL()
    {
        return BIN_LOOKUP_URL . $this->bankIdentificationNumber;
    }

    public function send()
    {
        try {
            $sourceUrl = $this->getBinURL();
            $result = file_get_contents($sourceUrl);

            $this->result = json_decode($result);

        } catch (\Exception $e) {
            error_log($e->getMessage());
        }

        return null;
    }

    public function getData()
    {
        return new CardMetaData($this->result);
    }
}