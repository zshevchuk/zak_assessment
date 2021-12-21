<?php

namespace App\Tests\Lib\FileParser;

use App\Lib\FileParser\LogDataProcessor;

class LogDataProcessorTest extends \PHPUnit\Framework\TestCase
{
    public ?LogDataProcessor $LogDataProcessor;

    public function setUp(): void
    {
        $this->LogDataProcessor = new LogDataProcessor();
    }

    public function tearDown(): void
    {
        $this->LogDataProcessor = null;
    }

    public function provideBooksHash(): array
    {
        return [
            [['book_1', 'book_2'], 2],
            [['book_1', 'book_2', 'book_3'], 3],
            [[], 0],
        ];
    }

    /**
     * @dataProvider provideBooksHash
     */
    public function testGetCurrentCheckoutedBooksCount($books, $expected)
    {
        $this->LogDataProcessor->booksCheckoutMap = $books;
        $this->assertEquals($expected, $this->LogDataProcessor->getCurrentCheckoutedBooksCount());
    }

    public function provideArrays(): array
    {
        return [
            [['book_1' => 10, 'book_2' => 5, 'book_3' => 7], ['book_1']],
            [['book_1' => 2, 'book_2' => 5, 'book_3' => 7], ['book_3']],
            [['book_1' => 1, 'book_2' => 's', 'book_3' => 7], ['book_3']],
            [['book_1' => 1, 'book_2' => 7, 'book_3' => 7], ['book_2', 'book_3']],
            [['book_1' => 7, 'book_2' => 1, 'book_3' => 7], ['book_1', 'book_3']],
            [['book_1' => '7', 'book_2' => 1, 'book_3' => 7], [ 'book_3']],
            [['book_1' => '8', 'book_2' => 1, 'book_3' => 7], ['book_3']],
            [['book_1' => '7string', 'book_2' => 1, 'book_3' => 7], ['book_3']],
            [['book_1' => [1000000], 'book_2' => 1, 'book_3' => 7], ['book_3']],
            [['book_1' => [1000000], 'book_2' => 1, 'book_3' => null], ['book_2']],
        ];
    }

    /**
     * @dataProvider provideArrays
     */
    public function testFindKeysOfTheMaxValue($data, $expected)
    {
        $this->assertEquals($expected, $this->LogDataProcessor->findKeysOfTheMaxValue($data));
    }
}
