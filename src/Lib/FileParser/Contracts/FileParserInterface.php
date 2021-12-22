<?php

namespace App\Lib\FileParser\Contracts;

use App\Lib\FileParser\Abstracts\File;
use App\Lib\FileParser\LogDataProcessor;

interface FileParserInterface
{
    public function parse(File $file, LogDataProcessor $resultData);
}
