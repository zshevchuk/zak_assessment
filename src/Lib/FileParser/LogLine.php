<?php

namespace App\Lib\FileParser;

use App\Lib\FileParser\Contracts\LineInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;


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

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint(
            'bookId',
            new Assert\Length(['min' => 30])
        );
    }
}
