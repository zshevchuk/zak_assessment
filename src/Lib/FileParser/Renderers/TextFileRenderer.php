<?php

namespace App\Lib\FileParser\Renderers;

use App\Lib\FileParser\Contracts\RendererInterface;

class TextFileRenderer extends FileRenderer implements RendererInterface
{
    public function format(array $data): string
    {
        $output = '';

        foreach ($data as $key => $value) {
            $output .= "$key : $value";
        }

        return $output;
    }
}
