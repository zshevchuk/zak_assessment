<?php

namespace App\Lib\FileParser\Parsers;

use App\Lib\FileParser\Contracts\FileParser;
use App\Lib\FileParser\Abstracts\File;
use App\Lib\FileParser\LogLine;
use App\Lib\FileParser\LogDataProcessor;

class CsvFileParser implements FileParser
{
    public function parse(File $file, LogDataProcessor $resultData): bool
    {
        $row = 0;
        $import = fopen($file->getPathname(), 'r');

        if (!$import) {
            return false;
        }

        while ($data = fgetcsv($import)) {
            if (!$data) {
                return false;
            }
            $row++;

            // skip header row
            if ($row == 1) {
                continue;
            }

            $line = new LogLine(timestamp: $data[0], personId: $data[1], bookId: $data[2], actionType: $data[3]);
            $resultData->iterateOverLine($line);
        }

        return true;
    }
}
