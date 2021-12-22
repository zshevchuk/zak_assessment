<?php

namespace App\Lib\FileParser;

use App\Lib\FileParser\Abstracts\File;
use App\Lib\FileParser\Contracts\FileParserInterface;

class ParserFactory
{
    public static function create(File $file): FileParserInterface
    {
        $extension = $file->getExtension();

        $parserClass = ucfirst($extension) . 'FileParser';
        $namespaced = sprintf(__NAMESPACE__ . "\\Parsers\\%s", $parserClass);

        if (!class_exists($namespaced)) {
            throw new \RuntimeException($namespaced . ' does not exist');
        }

        return new $namespaced;
    }
}

