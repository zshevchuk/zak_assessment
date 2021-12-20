<?php

namespace App\Lib\FileParser\Contracts;

interface LineIterator
{
    public function iterateOverLine(Line $line): void;
}
