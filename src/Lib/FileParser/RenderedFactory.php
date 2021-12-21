<?php

namespace App\Lib\FileParser;

use App\Lib\FileParser\Abstracts\File;
use App\Lib\FileParser\Contracts\Renderer;

class RenderedFactory
{
    public static function create(File $file): Renderer
    {
        $extension = $file->getExtension();

        $parserClass = ucfirst($extension) . 'FileRenderer';
        $namespaced = sprintf(__NAMESPACE__ . "\\Renderers\\%s", $parserClass);

        if (!class_exists($namespaced)) {
            throw new \RuntimeException($namespaced . ' does not exist');
        }

        return new $namespaced($file);
    }
}

