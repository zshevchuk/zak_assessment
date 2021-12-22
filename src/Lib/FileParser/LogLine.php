<?php

namespace App\Lib\FileParser;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use App\Lib\FileParser\Contracts\LineInterface;

use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
* @Assert\EnableAutoMapping()
*/
final class LogLine implements LineInterface
{
    /**
     * @Assert\GreaterThan(50)
     */
    public int $personId;
    /**
     * @Assert\GreaterThan(50)
     */
    public string $bookId;
    /**
     * @Assert\GreaterThan(50)
     */
    public string $timestamp;
    /**
     * @Assert\GreaterThan(50)
     */
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
