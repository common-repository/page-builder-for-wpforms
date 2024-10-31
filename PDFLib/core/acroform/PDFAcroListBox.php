<?php


namespace rnpagebuilder\js\src\lib\PDFLib\core\acroform;


use rnpagebuilder\PDFLib\core\acroform\PDFAcroChoice;
use rnpagebuilder\PDFLib\core\integration\ReferenceArray;
use rnpagebuilder\PDFLib\core\PDFContext;

class PDFAcroListBox extends PDFAcroChoice
{
    public static function fromDict($dict,$ref)
    {
        return new PDFAcroListBox($dict,$ref);
    }

    /**
     * @param $context PDFContext
     */
    public static function create($context)
    {
        $dict=$context->obj((object)array(
           'FT'=>'Ch',
           'Kids'=>new ReferenceArray()
        ));

        $ref=$context->register($dict);
        return new PDFAcroListBox($dict,$ref);
    }
}