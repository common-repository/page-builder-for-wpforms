<?php


namespace rnpagebuilder\core\Integration\Adapters\WPForm\EntryEditor\Fields;


use rnpagebuilder\core\Integration\Adapters\WPForm\EntryEditor\Fields\Core\WPFormFieldEditorBase;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\ComposedFieldSettings;

class WPComposedFieldEditor extends WPFormFieldEditorBase
{

    /** @var ComposedFieldSettings */
    public $FieldSettings;
    public function PrepareProperties($properites)
    {
        $value=$this->Row->GetValue($this->FieldSettings->Id);
        if($value=='')
            return true;

        foreach($this->FieldSettings->Rows as $currentRow)
            foreach($currentRow->Items as $currentItem)
            {
                if(count($currentItem->Path)>1)
                    throw new \Exception('Value for field '.$this->FieldSettings->Id.' could not be processed');

                $id=$currentItem->Path[0];
                if(!isset($value->Raw->{$id})||!isset($properites['inputs'][$id]))
                    continue;

                $input=&$properites['inputs'][$id];
                if(!isset($input['attr']['value']))
                    continue;

                $input['attr']['value']=$value->Raw->{$id};
            }

        return $properites;
    }
}