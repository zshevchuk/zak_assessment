<?php

namespace App\Lib\FileParser;

use App\Lib\FileParser\Abstracts\File;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Validates file extension and calls fileProcessor to analyze log file
 */
class InputFile extends File
{
    protected static array $supportedExtensions = [
        'application/xml',
        'text/xml',
        'text/csv',
    ];
    public static string $folder = 'import';

//    public function __construct(string $filePath)
//    {
//        parent::__construct($filePath);
//
//        $this->validateFileExists();
//    }

    public function validateFileExists(): void
    {
        if (!file_exists($this->filePath)) {
            throw new \Exception(sprintf("File %s does not exist", $this->getPath()));
        }
    }

//    public static function loadValidatorMetadata(ClassMetadata $metadata)
//    {
//        $metadata->addPropertyConstraint('filePath', new Assert\File([
//            'mimeTypes' => [
//                'application/xml',
//                'text/xml',
//                'text/csv',
//            ],
//            'mimeTypesMessage' => 'Please upload a valid InputFile',
//        ]));
//    }
}

