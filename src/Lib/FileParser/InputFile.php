<?php

namespace App\Lib\FileParser;

use App\Lib\FileParser\Abstracts\File;

/**
 * Validates file extension and calls fileProcessor to analyze log file
 */
class InputFile extends File
{
    protected array $supportedExtensions = ['xml', 'csv'];
    public static string $folder = 'import';

    public function __construct(string $filePath)
    {
        parent::__construct($filePath);

        $this->validateFileExists();
    }

    public function validateFileExists(): void
    {
        if (!file_exists($this->filePath)) {
            throw new \Exception(sprintf("File %s does not exist", $this->getFilePath()));
        }
    }
}

