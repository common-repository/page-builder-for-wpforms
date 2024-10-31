<?php

namespace rnpagebuilder\PageGenerator\TextRenderer;



class FieldTextRenderer extends TextTextRenderer
{
    /** @var FieldBase */
    public $Field;

    public function GetText()
    {

        $fieldId=$this->Content->attrs->id;
        $fieldPath=$this->Content->attrs->path;
        $fieldOptions=$this->Content->attrs->options;

        return $this->GetEntryRetriever()->GetCurrentRowStringValue($fieldId,$fieldPath);

    }


}