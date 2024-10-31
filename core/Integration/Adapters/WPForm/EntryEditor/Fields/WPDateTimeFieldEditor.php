<?php


namespace rnpagebuilder\core\Integration\Adapters\WPForm\EntryEditor\Fields;


use rnpagebuilder\core\Integration\Adapters\WPForm\EntryEditor\Fields\Core\WPFormFieldEditorBase;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\TimeFieldSettings;
use rnpagebuilder\Utilities\Sanitizer;

class WPDateTimeFieldEditor extends WPFormFieldEditorBase
{

    public function PrepareProperties($properites)
    {
        $value=$this->Row->GetValue($this->FieldSettings->Id);
        if($this->FieldSettings instanceof TimeFieldSettings)
        {
            $this->SetPropertyValue($properites,['inputs','time','attr','value'],Sanitizer::GetStringValueFromPath($value,['Value']));
        }

        return $properites;
    }
}