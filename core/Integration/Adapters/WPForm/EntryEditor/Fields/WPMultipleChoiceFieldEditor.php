<?php


namespace rnpagebuilder\core\Integration\Adapters\WPForm\EntryEditor\Fields;


use rnpagebuilder\core\Integration\Adapters\WPForm\EntryEditor\Fields\Core\WPFormFieldEditorBase;
use rnpagebuilder\Utilities\Sanitizer;

class WPMultipleChoiceFieldEditor extends WPFormFieldEditorBase
{

    public function PrepareProperties($properties)
    {
        $value=$this->Row->GetStringValue($this->FieldSettings->Id);

        if(!is_array($value))
            $value=[$value];

        foreach ($properties['inputs'] as $currentInput)
        {
            if (!isset($currentInput['default']))
                $currentInput['default'] = false;
        }

        foreach($value as $currentValue)
        {
            foreach ($properties['inputs'] as &$currentInput)
            {
                if (Sanitizer::GetValueFromPath($currentInput,['label','text'])==$currentValue)
                {
                    $currentInput['default'] = true;
                }
            }
        }



        return $properties;
    }
}