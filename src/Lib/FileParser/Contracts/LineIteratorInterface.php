<?php

namespace App\Lib\FileParser\Contracts;

interface LineIteratorInterface
{
    public function iterateOverLine(LineInterface $line): void;
}
