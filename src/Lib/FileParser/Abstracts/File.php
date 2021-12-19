<?php

namespace App\Lib\FileParser\Abstracts;

abstract class File
{
    protected string $filePath;
    protected array $supportedExtensions;
    public static string $folder = '';

    public function __construct(string $filePath)
    {
        $this->filePath = $this->createFilePath($filePath);


        $this->validateFileExtensions();
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    public function validateFileExtensions(): void
    {
        $extension = $this->getFileExtension();

        if (!in_array($extension, $this->supportedExtensions)) {
            $message = "Invalid input file extension was provided for " . get_class($this);

            throw new \Exception($message);
        }
    }

     public function getFileExtension(): string
    {
        return strtolower(pathinfo($this->getFilePath(), PATHINFO_EXTENSION));
    }

    public function createFilePath($filePath): string
    {
        $publicFolder = 'public';

        if (!static::$folder) {
            return $publicFolder . DIRECTORY_SEPARATOR . $filePath;
        }

        return $publicFolder . DIRECTORY_SEPARATOR . static::$folder . DIRECTORY_SEPARATOR . $filePath;
    }
}

