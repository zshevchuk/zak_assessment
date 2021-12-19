<?php

namespace App\Lib\FileParser\Renderers;

use App\Lib\FileParser\Contracts\Renderer;
use App\Lib\FileParser\ResultData;

class TextFileRenderer extends FileRenderer implements Renderer
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
