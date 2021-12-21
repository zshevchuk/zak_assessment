<?php

namespace App\Lib\FileParser\Abstracts;

use Symfony\Component\HttpFoundation\File\File as SymfonyFile;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;


abstract class File extends SymfonyFile
{
//    protected string $filePath;
    protected static array $supportedExtensions = [];
    public static string $folder = '';

    public function __construct(string $path, bool $checkPath = true)
    {
        $path = $this->createFilePath($path);

//        $this->validateFileExtensions();

        parent::__construct($path, $checkPath);
    }

//    public function validateFileExtensions(): void
//    {
//        $extension = $this->getExtension();
//        dump($extension);
//
//        if (!in_array($extension, $this->supportedExtensions)) {
//            $message = "Invalid input file extension was provided for " . get_class($this);
//
//            throw new \Exception($message);
//        }
//    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('bioFile', new Assert\File([
            'mimeTypes' => static::$supportedExtensions,
            'mimeTypesMessage' => 'Please upload a valid file',
        ]));
    }

//     public function getExtension(): string
//    {


//        return strtolower(pathinfo($this->getPath(), PATHINFO_EXTENSION));
//    }

    public function createFilePath($filePath): string
    {
        $publicFolder = 'public';

        if (!static::$folder) {
            return $publicFolder . DIRECTORY_SEPARATOR . $filePath;
        }

        return $publicFolder . DIRECTORY_SEPARATOR . static::$folder . DIRECTORY_SEPARATOR . $filePath;
    }
}

