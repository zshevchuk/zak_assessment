<?php

namespace App\Lib\FileParser\Contracts;

interface DataProcessorInterface
{
    public function iterateOverLine(LineInterface $line): void;

    public function response(): array;
}
