<?php

namespace App\Utils;

use App\Data\InputData;

class InputFileReader
{
    protected $file_path;
    protected $content;

    public function setFile($file)
    {
        $this->file_path = $file;
    }

    public function read()
    {
        $this->content = explode("\n", file_get_contents($this->file_path));
    }

    public function getContent()
    {
        return $this->content;
    }
}