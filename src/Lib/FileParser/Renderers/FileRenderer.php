<?php

namespace App\Lib\FileParser\Renderers;

use App\Lib\FileParser\Abstracts\File;
use App\Lib\FileParser\Contracts\RendererInterface;

abstract class FileRenderer implements RendererInterface
{
    protected File $file;


    public function __construct(File $file)
    {
        $this->file = $file;
    }

    abstract function format(array $data);

    public function render(array $data):void
    {
        $file = fopen($this->file->getPathname(), 'w');

        if (!$file) {
            throw new \Exception('Could not open output folder');
        }

        $output = $this->format($data);

        if (!fwrite($file, $output)) {
            throw new \Exception('Could not write into output file');
        }
    }

}

