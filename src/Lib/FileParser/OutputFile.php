<?php

namespace App\Lib\FileParser;

use App\Lib\FileParser\Abstracts\File;

class OutputFile extends File
{
    protected static array $supportedExtensions = [
        'text/plain',
        'application/json',
    ];
    public static string $folder = 'result';

    public function __construct(string $path, bool $checkPath = true)
    {
        $path = $this->createFilePath($path);

        parent::__construct($path, false);
    }


    public static function defaultFilename()
    {
        $now = new \DateTime('now');

        return sprintf('result_%s.json', $now->format('U'));
    }
}

