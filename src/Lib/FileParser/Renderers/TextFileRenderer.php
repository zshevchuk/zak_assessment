<?php

namespace App\Lib\FileParser\Renderers;

use App\Lib\FileParser\Contracts\RendererInterface;
use Symfony\Component\String\Inflector\EnglishInflector;
use Symfony\Component\String\Inflector\InflectorInterface;

class TextFileRenderer extends FileRenderer implements RendererInterface
{
    protected ?InflectorInterface $inflector = null;

    public function format(array $data): string
    {
        $output = '';

        foreach ($data as $key => $value) {

            $output .= $this->createRow($key, $value);
        }

        return $output;
    }

    private function createRow(string $key, int|array $value)
    {
        $inflector = $this->getInflector();
        if (is_array($value)) {
            $inflector->pluralize($key);
            $value = implode(",", $value);
        }

        return "$key : $value" . PHP_EOL;
    }

    private function getInflector()
    {
        if (null !== $this->inflector) {
            return $this->inflector;
        }

        $this->inflector = new EnglishInflector();
        return $this->inflector;
    }
}
