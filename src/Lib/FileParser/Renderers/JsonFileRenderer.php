<?php

namespace App\Lib\FileParser\Renderers;

use App\Lib\FileParser\Contracts\RendererInterface;

class JsonFileRenderer extends FileRenderer implements RendererInterface
{
    public function format(array $data): string
    {
        return json_encode($data);
    }
}
