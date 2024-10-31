<?php


namespace rnpagebuilder\core\Integration\Processors\Entry\EntryItems\MultipleSelectionEntryItem;


class MultipleSelectionValueItem{
    public $Value='';
    public $Amount=0;
    public $RawAmount=0;

    public function InitializeWithValues($value,$amount,$rawAmount=0)
    {
        $this->Value=$value;
        $this->Amount=$amount;
        $this->RawAmount=$rawAmount;
        return $this;

    }

    public function InitializeWithOptions($CurrentItem)
    {
        if(isset($CurrentItem->Value))
            $this->Value=$CurrentItem->Value;
        if(isset($CurrentItem->Amount))
            $this->Amount=$CurrentItem->Amount;
        if(isset($CurrentItem->RawAmount))
            $this->RawAmount=\floatval($CurrentItem->RawAmount);
        return $this;
    }


}