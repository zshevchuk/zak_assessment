<?php

namespace App\Lib\FileParser\Abstracts;

use Symfony\Component\HttpFoundation\File\File as SymfonyFile;

abstract class File extends SymfonyFile
{
    protected array $supportedExtensions = [];

    public static string $folder;

    public function __construct(string $path, bool $checkPath = true)
    {
        $path = $this->createFilePath($path);

        $this->validateFileExtensions($path);;

        parent::__construct($path, $checkPath);
    }

    public function validateFileExtensions(string $path): void
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if (!in_array($extension, $this->supportedExtensions)) {
            $message = "Invalid input file extension was provided for " . get_class($this);

            throw new \Exception($message);
        }
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

