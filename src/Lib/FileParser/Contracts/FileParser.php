<?php

namespace App\Lib\FileParser\Contracts;

use App\Lib\FileParser\Abstracts\File;
use App\Lib\FileParser\ResultData;

interface FileParser
{
    public function parse(File $file, ResultData $resultData);
}
