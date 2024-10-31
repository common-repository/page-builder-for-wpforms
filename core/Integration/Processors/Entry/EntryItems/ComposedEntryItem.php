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
use rnpagebuilder\core\Integration\Processors\Entry\HTMLFormatters\MultipleBoxFormatter\SingleBoxFormatter;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\ComposedFieldSettings;

class ComposedEntryItem extends EntryItemBase
{
    public $Value;
    /** @var ComposedFieldSettings */
    public $Field;
    public function SetValue($value)
    {
        $this->Value=$value;
        return $this;
    }


    protected function InternalGetObjectToSave()
    {
        return (object)array(
            'Value'=>$this->GetText(),
            'Raw'=>$this->Value
        );
    }

    public function InitializeWithOptions($field,$options)
    {
        $this->Field=$field;
        if(isset($options->Value))
            $this->Value=$options->Value;
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
        $text='';
        foreach($this->Field->Rows as $currentRow)
        {
            foreach($currentRow->Items as $currentItem)
                $text=$this->AddItem($currentItem,$this->Value,$text);

        }
        return $text;
    }


    private function AddItem($currentItem, $value,$text) {
        foreach($currentItem->Path as $currentPath )
        {
            if(!isset($value->$currentPath))
            {
                $value='';
                break;
            }

            $value=$value->$currentPath;
        }

        if(\is_object($value)||\is_array($value))
            $value='';
        $value=\strval($value);

        if($value=='')
            return $text;

        if($text!='')
        {
            if($currentItem->AddCommaBefore)
                $text.=', ';
            else
                $text.=' ';
        }

        $text.=$value;
        return $text;

    }

    public function GetItemValue($itemId)
    {
        $value=$this->Value;
        foreach($this->Field->Rows as $currentRow)
            foreach($currentRow->Items as $currentItem)
            {
                if($currentItem->Id==$itemId)
                {
                    foreach($currentItem->Path as $currentPath )
                    {
                        if(!isset($value->$currentPath))
                        {
                            return null;
                        }

                        $value=$value->$currentPath;
                    }

                    return $value;
                }

            }

        return null;
    }

    public function ProcessFieldMethod($methodName)
    {
        $value=$this->GetItemValue($methodName);
        if($value==null)
            return '';

        return $value;
    }

    public function InternalGetDetails($base)
    {


        $defaultValue=$base->CloneItem();
        $defaultValue->Value=$this->GetText();
        $defaultValue->PathId=null;

        $itemList=array();
        $itemList[]=$defaultValue;


        foreach($this->Field->Rows as $currentRow)
        {
            foreach($currentRow->Items as $currentItem)
            {
                $value = $this->GetItemValue($currentItem->Id);

                if ($value == null || trim($value) == '')
                    continue;

                $newItem = $base->CloneItem();
                $newItem->Value = $value;
                $newItem->PathId = $currentItem->Id;
                $itemList[] = $newItem;
            }

        }

        return $itemList;


    }


    public function GetType()
    {
        return 'composed';
    }

    public function IsEmpty()
    {
        foreach($this->Field->Rows as $currentRow)
            foreach($currentRow->Items as $currentItem)
            {
                $value = $this->GetItemValue($currentItem->Id);

                if ($value != null && trim($value) != '')
                    return false;
            }

        return true;
    }
}