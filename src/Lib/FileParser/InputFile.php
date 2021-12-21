<?php

namespace App\Lib\FileParser;

use App\Lib\FileParser\Abstracts\File;

class InputFile extends File
{
    protected array $supportedExtensions = [
        'xml',
        'csv',
    ];

    public static string $folder = 'import';
}

