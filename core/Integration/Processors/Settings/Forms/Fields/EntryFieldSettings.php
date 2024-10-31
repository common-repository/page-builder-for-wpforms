<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/21/2019
 * Time: 5:05 AM
 */

namespace rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields;
use rnpagebuilder\DTO\ManuallyCreated\DataSection;

class EntryFieldSettings extends FieldSettingsBase
{

    public function GetType()
    {
        return "EntryId";
    }

    public function GetDataSections($mode='Filter'){
        $sections=[];
        $sections[]=new DataSection($this->Id,$this->Label,'Value',null);
        return $sections;
    }
}