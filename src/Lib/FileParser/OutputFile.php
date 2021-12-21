<?php

namespace App\Lib\FileParser;

use App\Lib\FileParser\Abstracts\File;

class OutputFile extends File
{
    protected array $supportedExtensions = [
        'txt',
        'text',
        'json',
    ];

    public static string $folder = 'result';

    public function __construct(string $path, bool $checkPath = true)
    {
        parent::__construct($path, false);
    }

    public static function getDefaultName(): string
    {
        $now = new \DateTime('now');

        return sprintf('result_%s.json', $now->format('U'));
    }
}

