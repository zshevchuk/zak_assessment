<?php

namespace App\Lib\FileParser\Contracts;

use App\Lib\FileParser\ResultData;

interface Renderer
{
    public function render(array $data);
}
