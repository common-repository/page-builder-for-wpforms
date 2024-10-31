<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/22/2019
 * Time: 5:50 AM
 */

namespace rnpagebuilder\core\Integration\Processors\Entry\EntryItems;


use rnpagebuilder\core\Integration\Processors\Entry\EntryItems\Core\EntryItemBase;
use rnpagebuilder\core\Integration\Processors\Entry\HTMLFormatters\BasicPHPFormatter;

class SimpleTextWithAmountEntryItem extends EntryItemBase
{
    public $Value;
    public $Amount;
    public function SetValue($value,$amount)
    {
        $this->Value=$value;
        $this->Amount=$amount;
        return $this;
    }


    protected function InternalGetObjectToSave()
    {
        return (object)array(
            'Value'=>$this->Value,
            'Amount'=>$this->Amount
        );
    }

    public function InitializeWithOptions($field,$options)
    {
        $this->Field=$field;
        if(isset($options->Value))
            $this->Value=$options->Value;
        if(isset($options->Amount))
            $this->Amount=$options->Amount;
    }

    public function GetHtml($style='standard')
    {
        return new BasicPHPFormatter($this->Value);
    }


    public function GetText()
    {
        return $this->Value;
    }


    public function GetType()
    {
        return 'textwithamount';
    }

    public function IsEmpty()
    {
        return $this->Value=='';
    }

    public function InternalGetDetails($base)
    {

        $base->Value=$this->Value;
        $base->NumericValue=$this->Amount;
        return [$base];
    }


}