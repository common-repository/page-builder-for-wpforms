<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/21/2019
 * Time: 4:59 AM
 */

namespace rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields;
use rnpagebuilder\DTO\ManuallyCreated\DataSection;
use rnpagebuilder\Utilities\Sanitizer;

class MultipleOptionsFieldSettings extends FieldSettingsBase
{
    /** @var MultipleOptionsItem */
    public $Items;

    public function __construct()
    {
        $this->Items=[];
    }

    public function GetAggregationPath()
    {
        return '1';
    }


    public function GetType()
    {
        return "Multiple";
    }

    public function AddOption($label,$value,$price=''){
        $this->Items[]=new MultipleOptionsItem($label,$value!=''?$value:$label,$price);
    }

    public function InitializeFromOptions($options)
    {
        parent::InitializeFromOptions($options);
        foreach($options->Items as $Item)
        {
            $price='';
            if(isset($Item->Price))
                $price=$Item->Price;
            $this->Items[]=new MultipleOptionsItem($Item->Label,$Item->Value,$price);
        }
    }

    public function IsOptionSelected($option,$value)
    {
        if($value==null)
            return false;
        foreach ($value->Values as $currentValue)
            if($option->Value==$currentValue||$option->Label==$currentValue)
                return true;
        return false;
    }


    public function GetDataSections($mode='Filter'){
        return [new DataSection($this->Id,$this->Label,$mode=='Display'?'Value':'1',['value'])];
    }

    public function ParseValue($value, $pathId = null)
    {
        $value=str_replace("\n",", ",parent::ParseValue($value, $pathId));
        return $value;
    }

    public function ParseHTMLValue($value, $pathId = null)
    {
        $valueToCheck=Sanitizer::GetValueFromPath($value,['value']);
        if(is_array($valueToCheck)&&count($valueToCheck)==0)
            return '';
        $value= parent::ParseValue($value, $pathId);
        $value=nl2br($value);

        return $value;
    }

}


class MultipleOptionsItem{
    public $Label;
    public $Value;
    public $Price;

    public function __construct($label,$value,$price='')
    {
        $this->Label=$label;
        $this->Value=$value;
        $this->Price=$price;
    }


}