<?php

namespace App\Lib\FileParser;

use App\Lib\FileParser\Abstracts\File;

class OutputFile extends File
{
    protected array $supportedExtensions = ['txt', 'text', 'json'];
    public static string $folder = 'result';

    public static function defaultFilename()
    {
        return sprintf('result_%s.json', time());
    }
}

