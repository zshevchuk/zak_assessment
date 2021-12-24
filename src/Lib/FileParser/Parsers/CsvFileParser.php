<?php

namespace App\Lib\FileParser\Parsers;

use App\Lib\FileParser\Contracts\FileParserInterface;
use App\Lib\FileParser\Abstracts\File;
use App\Lib\FileParser\LogLine;
use App\Lib\FileParser\LogDataProcessor;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CsvFileParser implements FileParserInterface
{
    public function __construct(private ValidatorInterface $validator)
    {
    }
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
            $violations = $this->validator->validate($line);
            dump($violations);
            $resultData->iterateOverLine($line);
        }

        return true;
    }
}
