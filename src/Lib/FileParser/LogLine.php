<?php

namespace App\Lib\FileParser;

use App\Lib\FileParser\Contracts\LineInterface;

final class LogLine implements LineInterface
{
    public int $personId;
    public string $bookId;
    public string $timestamp;
    public string $actionType;

    public function __construct(string $timestamp, int $personId, string $bookId, string $actionType)
    {
        $this->personId = $personId;
        $this->bookId = $bookId;
        $this->timestamp = strtotime($timestamp);
        $this->actionType = $actionType;
    }
}
