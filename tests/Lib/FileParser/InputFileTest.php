<?php

namespace App\Tests\Lib\FileParser;

use App\Lib\FileParser\Enums\LogEnum;
use App\Lib\FileParser\LogLine;

class InputFileTest extends \PHPUnit\Framework\TestCase
{
    public function provideLines(): array
    {
        return [
            [['text.xml'], true],
            [['test.csv'], true],
            [['test.css'], false],
        ];
    }

    /**
     * @dataProvider provideLines
     */
    public function testConstructorValidation($line, $expected)
    {
        try {
            $line = new LogLine($line[0], $line[1], $line[2], $line[3]);
        } catch (\Exception $e)
        {
            dump('1');
            $error = true;
        }


        $this->assertEquals($expected, isset($line));
    }

    public function testCreateFilePath()
    {

    }

}
