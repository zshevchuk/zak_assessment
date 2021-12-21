<?php

namespace App\Lib\FileParser\Parsers;

use App\Lib\FileParser\Contracts\FileParser;
use App\Lib\FileParser\Abstracts\File;
use App\Lib\FileParser\LogLine;
use App\Lib\FileParser\LogDataProcessor;

class XmlFileParser implements FileParser
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
            // now that we're at the right depth, hop to the next <record/> until the end of the tree
            while ($xml->name === 'record') {
                $element = (array)new \SimpleXMLElement($xml->readOuterXML());
                $personId = (string)$element['person']->attributes()['id'];
                $actionType = (string)$element['action']->attributes()['type'];

                $line = new LogLine(timestamp: $element['timestamp'], personId: $personId, bookId: $element['isbn'], actionType: $actionType);

                $resultData->iterateOverLine($line);

                $atLeastOneIteration = true;
                $xml->next('record');
            }
        }


        return $atLeastOneIteration;
    }

}
