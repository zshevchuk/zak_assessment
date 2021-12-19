<?php

namespace App\Lib\FileParser\Parsers;

use App\Lib\FileParser\Contracts\FileParser;
use App\Lib\FileParser\Abstracts\File;
use App\Lib\FileParser\Line;
use App\Lib\FileParser\ResultData;

class CsvFileParser implements FileParser
{
    /**
     * Based on a file time processed file
     */
    public function parse(File $file, ResultData $resultData)
    {
        $row = 0;
        $import = fopen($file->getFilePath(), 'r');

        while ($data = fgetcsv($import)) {
            $row++;

            // skip header row
            if ($row == 1) {
                continue;
            }

            $line = new Line(timestamp: $data[0], personId: $data[1], bookId: $data[2], actionType: $data[3]);
            $resultData->feedLine($line);
        }

        return true;
    }
}
