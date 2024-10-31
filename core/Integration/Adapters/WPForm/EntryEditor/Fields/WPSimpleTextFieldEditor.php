<?php


namespace rnpagebuilder\core\Integration\Adapters\WPForm\EntryEditor\Fields;


use rnpagebuilder\core\Integration\Adapters\WPForm\EntryEditor\Fields\Core\WPFormFieldEditorBase;

class WPSimpleTextFieldEditor extends WPFormFieldEditorBase
{

    public function PrepareProperties($properties)
    {
        $value=$this->Row->GetStringValue($this->FieldSettings->Id);
        if($value=='')
            return $properties;
         $this->SetPropertyValue($properties,['inputs','primary','attr','value'],$value);
        return $properties;
    }
}