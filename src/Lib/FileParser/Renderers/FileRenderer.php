<?php

namespace App\Lib\FileParser\Renderers;

use App\Lib\FileParser\Abstracts\File;
use App\Lib\FileParser\Contracts\Renderer;

abstract class FileRenderer implements Renderer
{
    protected File $file;

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    abstract function format(array $data);

    public function render(array $data)
    {
        $file = fopen($this->file->getExtension(), 'w');

        if (!$file) {
            throw new \Exception('Could not open output folder');
        }

        $output = $this->format($data);

        return (bool)fwrite($file, $output);
    }

}

