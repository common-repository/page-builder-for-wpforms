<?php


namespace rnpagebuilder\PDFLib\core\acroform;


class PDFAcroSignature extends PDFAcroTerminal
{
    public static function fromDict($dict,$ref)
    {
        return new PDFAcroSignature($dict,$ref);
    }
}