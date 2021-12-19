<?php

namespace App\Lib\FileParser\Parsers;

use App\Lib\FileParser\Contracts\FileParser;
use App\Lib\FileParser\Abstracts\File;
use App\Lib\FileParser\Line;
use App\Lib\FileParser\ResultData;

class XmlFileParser implements FileParser
{
    public function parse(File $file, ResultData $resultData)
    {
        $xml = new \XMLReader();
        $xml->open($file->getFilePath());

        // move to the first <record/> node
        while ($xml->read() && $xml->name !== 'record');

        // now that we're at the right depth, hop to the next <record/> until the end of the tree
        while ($xml->name === 'record') {
            $element = (array)new \SimpleXMLElement($xml->readOuterXML());
            $personId = (string)$element['person']->attributes()['id'];
            $actionType = (string)$element['action']->attributes()['type'];

            $line = new Line(timestamp: $element['timestamp'], personId: $personId, bookId: $element['isbn'], actionType: $actionType);
            $resultData->feedLine($line);

            $xml->next('record');
        }
    }

}
