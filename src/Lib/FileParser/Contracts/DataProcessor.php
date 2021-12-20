<?php

namespace App\Lib\FileParser\Contracts;

interface DataProcessor
{
    public function iterateOverLine(Line $line): void;

    public function response(): array;
}
