<?php


namespace rnpagebuilder\PDFLib\core\acroform;


use rnpagebuilder\PDFLib\core\integration\ReferenceArray;
use rnpagebuilder\PDFLib\core\PDFContext;

class PDFAcroComboBox extends PDFAcroChoice
{
    public static function fromDict($dict,$ref)
    {
        return new PDFAcroComboBox($dict,$ref);
    }

    /**
     * @param $context PDFContext
     */
    public static function create($context)
    {
        $dict=$context->obj((object)array(
            'FT'=>'Ch',
            'Ff'=>AcroChoiceFlags::$Combo,
            'Kids'=>new ReferenceArray()
        ));

        $ref=$context->register($dict);
        return new PDFAcroComboBox($dict,$ref);


    }
}