<?php


namespace rnpagebuilder\PDFLib\core\acroform;


use rnpagebuilder\PDFLib\core\acroform\AcroButtonFlags;
use rnpagebuilder\PDFLib\core\acroform\PDFAcroButton;
use rnpagebuilder\PDFLib\core\integration\ReferenceArray;
use rnpagebuilder\PDFLib\core\PDFContext;
use rnpagebuilder\PDFLib\utils\arrays;

class PDFAcroPushButton extends PDFAcroButton
{
    public static function fromDict($dict,$ref)
    {
        return new PDFAcroPushButton($dict,$ref);
    }

    /**
     * @param $context PDFContext
     */
    public static function create($context)
    {
        $dict=$context->obj((object)array(
           'FT'=>'BTN',
           'Ff'=>AcroButtonFlags::$PushButton,
           'Kids'=>new ReferenceArray()
        ));

        $ref=$context->register($dict);
        return new PDFAcroPushButton($dict,$ref);
    }
}