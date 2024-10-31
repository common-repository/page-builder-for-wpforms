<?php


namespace rnpagebuilder\core\Integration\Processors\Entry\EntryItems\Core;


use stdClass;

class EntryDetailItem
{
    public $EntryId=null;
    public $FieldId=null;
    public $UniqId=null;
    public $Value=null;
    public $ExValue1=null;
    public $ExValue2=null;
    public $ExValue3=null;
    public $ExValue4=null;
    public $ExValue5=null;
    public $ExValue6=null;
    public $NumericValue=null;
    public $NumericValue2=null;
    public $DateValue=null;
    public $DateValue2=null;
    public $Type=null;
    public $OriginalType=null;
    public $SubType=null;
    public $PathId=null;
    public $FormId;

    public function ToObject(){
        $data=new stdClass();
        $data->form_id=$this->FormId;
        $data->field_id=$this->FieldId;
        $data->value=$this->Value;
        $data->entry_id=$this->EntryId;
        $data->uniq_id=$this->UniqId;
        $data->type=$this->Type;
        $data->original_type=$this->OriginalType;
        $data->sub_type=$this->SubType;
        $data->path_id=$this->PathId;

        if($this->ExValue1!=null)
            $data->exvalue1=$this->ExValue1;
        if($this->ExValue2!=null)
            $data->exvalue2=$this->ExValue2;
        if($this->ExValue3!=null)
            $data->exvalue3=$this->ExValue3;
        if($this->ExValue4!=null)
            $data->exvalue4=$this->ExValue4;
        if($this->ExValue5!=null)
            $data->exvalue5=$this->ExValue5;
        if($this->ExValue6!=null)
            $data->exvalue6=$this->ExValue6;

        if($this->NumericValue!==null)
            $data->numericvalue=$this->NumericValue;

        if($this->NumericValue2!==null)
            $data->numericvalue2=$this->NumericValue2;

        if($this->DateValue!==null)
            $data->datevalue=$this->DateValue;

        if($this->DateValue2!==null)
            $data->datevalue2=$this->DateValue2;


        return (array)$data;


    }

    public function CloneItem()
    {
        $newItem=new EntryDetailItem();
        $newItem->UniqId=$this->UniqId;
        $newItem->EntryId=$this->EntryId;
        $newItem->Type=$this->Type;
        $newItem->FieldId=$this->FieldId;
        $newItem->DateValue=$this->DateValue;
        $newItem->DateValue2=$this->DateValue2;
        $newItem->ExValue1=$this->ExValue1;
        $newItem->ExValue2=$this->ExValue2;
        $newItem->ExValue3=$this->ExValue3;
        $newItem->ExValue4=$this->ExValue1;
        $newItem->ExValue5=$this->ExValue1;
        $newItem->ExValue6=$this->ExValue1;
        $newItem->OriginalType=$this->OriginalType;
        $newItem->SubType=$this->SubType;
        $newItem->PathId=$this->PathId;
        $newItem->FormId=$this->FormId;

        return $newItem;
    }

}