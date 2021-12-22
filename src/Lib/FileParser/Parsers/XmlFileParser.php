<?php

namespace App\Lib\FileParser\Parsers;

use App\Lib\FileParser\Contracts\FileParserInterface;
use App\Lib\FileParser\Abstracts\File;
use App\Lib\FileParser\LogLine;
use App\Lib\FileParser\LogDataProcessor;

class XmlFileParser implements FileParserInterface
{
    public function parse(File $file, LogDataProcessor $resultData): bool
    {
        $xml = new \XMLReader();

        if (!$xml->open($file->getRealPath())) {
            return false;
        }

        $atLeastOneIteration = false;

        // move to the first <record/> node
        while ($xml->read()) {
            while ($xml->name === 'record') {
                $element = (array)new \SimpleXMLElement($xml->readOuterXML());
                $personId = $this->extractPersonId($element);
                $actionType = $this->extractActionType($element);

                $line = new LogLine(timestamp: $element['timestamp'], personId: $personId, bookId: $element['isbn'], actionType: $actionType);

                $resultData->iterateOverLine($line);

                $atLeastOneIteration = true;
                $xml->next('record');
            }
        }

        return $atLeastOneIteration;
    }

    private function extractPersonId($element): ?int
    {
       return (string)$element['person']->attributes()['id'] ?? null;
    }

    private function extractActionType($element): ?string
    {
        return (string)$element['action']->attributes()['type'] ?? null;
    }
}
