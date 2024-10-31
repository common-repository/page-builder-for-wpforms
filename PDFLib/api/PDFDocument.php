<?php


namespace rnpagebuilder\PDFLib\api;


use rnpagebuilder\PDFLib\core\integration\ReferenceArray;
use rnpagebuilder\PDFLib\core\parser\PDFParser;
use rnpagebuilder\PDFLib\utils\arrays;

class PDFDocument
{
    public $bytes;
    public static function load($pdf)
    {
        $bytes=new ReferenceArray();
        for($i=0;$i<\strlen($pdf);$i++)
        {
            $bytes[]=ord($pdf[$i]);
        }

        $context=PDFParser::forBytesWithOptions($bytes,100,true,true)->parseDocument();


    }

    public static function loadFromPath($path)
    {
        PDFDocument::load(\file_get_contents($path));
    }
}