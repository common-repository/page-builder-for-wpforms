<?php


namespace rnpagebuilder\core\Integration\Processors\Entry\EntryItems;


use rnpagebuilder\core\Integration\Processors\Entry\EntryItems\Core\EntryItemBase;
use rnpagebuilder\core\Utils\ArrayUtils;

class CurrencyEntryItem extends EntryItemBase
{
    public $Value;
    public $Amount;
    public $AmountRaw;
    public function SetValue($value)
    {
        $this->Value=$value;
        return $this;
    }

    public function SetAmount($amount)
    {
        $this->Amount=$amount;
        return $this;
    }

    public function SetAmountRaw($amount)
    {
        $this->AmountRaw=$amount;
        return $this;
    }

    protected function InternalGetObjectToSave()
    {
        return (object)array(
            'Value'=>$this->Value,
            'Amount'=>$this->Amount,
            'AmountRaw'=>$this->AmountRaw,
        );
    }

    public function InitializeWithOptions($field,$options)
    {
        $this->Field=$field;
        if(isset($options->Value))
        {
            $this->Value = $options->Value;
        }

        if(isset($options->NumericValue))
        {
            $this->AmountRaw = $options->AmountRaw;
        }

        if(isset($options->ExValue1))
        {
            $this->Amount = $options->Amount;
        }
    }

    public function InitializeWithString($field,$stringValue)
    {
        $this->Field=null;
        $this->Value=$stringValue;

    }

    public function GetHtml($style='standard')
    {
        if($style=='similar')
        {

            return new SingleBoxFormatter($this->Value);
        }
        return new BasicPHPFormatter($this->Value);
    }


    public function GetText()
    {
        return $this->Value;
    }

    public function GetType()
    {
        return 'currency';
    }

    public function IsEmpty()
    {
        return trim($this->Value)=='';
    }

    public function InternalGetDetails($base)
    {

        $base->Value=$this->Value;
        $base->NumericValue=$this->AmountRaw;
        $base->ExValue1=$this->Amount;
        return [$base];

    }


}

