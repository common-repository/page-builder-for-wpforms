<?php

namespace rnpagebuilder\core\Integration\Processors\Entry\EntryItems;

use rnpagebuilder\core\Integration\Processors\Entry\EntryItems\Core\EntryItemBase;
use rnpagebuilder\core\Integration\Processors\Entry\EntryItems\MultipleSelectionEntryItem\MultipleSelectionEntryItem;
use rnpagebuilder\core\Integration\Processors\Entry\EntryItems\MultipleSelectionEntryItem\MultipleSelectionValueItem;

class CurrencyMultipleEntryItem extends EntryItemBase
{
    public $Values=[];
    /** @var MultipleSelectionValueItem[] */
    public $Items=[];
    public $Amount='';
    public $AmountRaw='';

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



    public function SetValue($value,$amount=0)
    {
        $this->Values=[];
        if(\is_array($value))
        {
            $this->Values =[];
            foreach($value as $currentValue)
            {

                $currentValue=$this->GetLabel($currentValue);
                $this->Values[]=$currentValue;
                $this->Items[]=(new MultipleSelectionValueItem())->InitializeWithValues($currentValue,$amount);
            }
        }
        else
        {
            if($value=='')
                return $this;

            $value=$this->GetLabel($value);
            $this->Values[] = $value;
            $this->Items[] = (new MultipleSelectionValueItem())->InitializeWithValues($value, $amount);
        }

        return $this;
    }

    public function GetLabel($value)
    {
        if(isset($this->Field->Items))
        {
            foreach($this->Field->Items as $item)
            {
                if($item->Value==$value)
                    return $item->Label;
            }
        }

        return $value;

    }

    public function AddItem($value,$amount)
    {
        $value=$this->GetLabel($value);
        $this->Items[]=(new MultipleSelectionValueItem())->InitializeWithValues($value,$amount);
        if($this->Values==null)
            $this->Values=[];
        $this->Values[]=$value;

    }
    public function GetAmount(){
        if(count($this->Items)==0)
            return 0;
        return $this->Items[0]->Amount;
    }

    protected function InternalGetObjectToSave()
    {
        $value='';
        if(\count($this->Values)>0)
            $value=\implode('@;;@',$this->Values);
        return (object)Array(
            'Value'=>$value,
            'Values'=>$this->Values,
            'Items'=>$this->Items
        );
    }


    public function InitializeWithOptions($field,$options)
    {
        $this->Field=$field;
        $this->Values=[];

        if(isset($options->Items))
        {
            foreach($options->Items as $CurrentItem)
            {
                $item=(new MultipleSelectionValueItem())->InitializeWithOptions($CurrentItem);
                $this->Items[]=$item;
                $this->Values[]=$item->Value;
            }


            return;
        }


        if(isset($options->Values))
            if(\is_array($options->Values))
                $this->Values=$options->Values;
            else
                $this->Values[]=$options->Values;


    }

    public function Contains($value)
    {
        if(!\is_array($value))
            $value=[$value];

        foreach($value as $currentValue)
            if( \in_array($currentValue,$this->Values))
                return true;
        return false;
    }


    public function GetText()
    {
        return \implode(', ',$this->Values);

    }

    public function GetHtml($style='standard')
    {
        $value=implode(', ',$this->Values);
        /** @var MultipleOptionsFieldSettings $field */
        $field=$this->Field;
        if($style=='similar')
        {
            $formatter=new MultipleOptionsFormatter(MultipleOptionsFormatterType::$Checkbox);

            foreach($field->Items as $currentItem)
            {
                $isSelected=false;
                foreach($this->Values as $currentValue)
                    if($currentValue==$currentItem->Label)
                        $isSelected=true;

                $formatter->AddOption($currentItem->Label,$isSelected);
            }

            foreach($this->Values as $value)
                $formatter->AddOption($value,true);

            return $formatter;
        }
        return new BasicPHPFormatter($value);
    }

    public function InternalGetDetails($base)
    {

        $itemList=array();
        $newItem=$base->CloneItem();
        $value=[];
        foreach($this->Items as $currentItem)
        {
            $value[]=$currentItem->Value;
        }

        if(count($value)>0)
        {
            $newItem=$base->CloneItem();
            $newItem->Value=implode(',',$value);
            $newItem->NumericValue=$this->AmountRaw;
            $newItem->ExValue1=$this->Amount;
            $itemList[]=$newItem;
        }


        foreach($this->Items as $currentItem)
        {
            $newItem=$base->CloneItem();
            $newItem->Value=$currentItem->Value;
            $newItem->NumericValue=$currentItem->Amount;
            $newItem->PathId=1;

            $itemList[]=$newItem;
        }

        return $itemList;

    }


    public function GetType()
    {
        return 'curmultiple';
    }

    public function IsEmpty()
    {
        return count($this->Items)==0;
    }

}