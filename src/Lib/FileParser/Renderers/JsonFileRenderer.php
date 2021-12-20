<?php

namespace App\Lib\FileParser\Renderers;

use App\Lib\FileParser\Contracts\Renderer;

class JsonFileRenderer extends FileRenderer implements Renderer
{
    public function format(array $data): string
    {
        return json_encode($data);
    }
}
