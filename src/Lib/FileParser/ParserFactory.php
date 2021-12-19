<?php

namespace App\Lib\FileParser;

use App\Lib\FileParser\Abstracts\File;
use App\Lib\FileParser\Contracts\FileParser;

class ParserFactory
{
    public static function create(File $file): FileParser
    {
        $extension = pathinfo($file->getFilePath(), PATHINFO_EXTENSION);
        $parserClass = ucfirst($extension) . 'FileParser';
        $namespaced = sprintf(__NAMESPACE__ . "\\Parsers\\%s", $parserClass);

        if (!class_exists($namespaced)) {
            throw new \RuntimeException($namespaced . ' does not exist');
        }

        return new $namespaced;
    }
}

