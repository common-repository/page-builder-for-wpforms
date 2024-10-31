<?php


namespace rnpagebuilder\core\Integration\Adapters\WPForm\EntryEditor\Fields\Core;


use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\FieldSettingsBase;
use rnpagebuilder\PageBuilderOld\DataSources\FormDataSource\FormRow;

abstract class WPFormFieldEditorBase
{
    /** @var FormRow */
    public $Row;
    /** @var FieldSettingsBase */
    public $FieldSettings;
    public function __construct($row,$fieldSettings)
    {
        $this->Row=$row;
        $this->FieldSettings=$fieldSettings;
    }

    public function SetPropertyValue(&$properties,$path,$value)
    {
        while($currentPath=array_shift($path))
        {
            if(!isset($properties[$currentPath]))
                return false;

            $properties=&$properties[$currentPath];
        }

        $properties=$value;
        return true;
    }

    public abstract function PrepareProperties($properites);

}