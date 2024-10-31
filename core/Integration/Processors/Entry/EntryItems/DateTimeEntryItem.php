<?php /** @noinspection DuplicatedCode */


namespace rnpagebuilder\core\Integration\Processors\Entry\EntryItems;


use rnpagebuilder\core\Integration\Processors\Entry\EntryItems\Core\EntryItemBase;

class DateTimeEntryItem extends EntryItemBase
{
    public $Unix;
    public $Value;

    public function GetText()
    {
        return $this->Value;
    }

    public function SetUnix($value)
    {
        $this->Unix=$value;
        return $this;
    }

    public function SetValue($value)
    {
        $this->Value=$value;
        return $this;
    }

    protected function InternalGetObjectToSave()
    {
        return (object)array(
            'Unix'=>$this->Unix,
            'Value'=>$this->Value
        );
    }

    public function InitializeWithOptions($field,$options)
    {
        $this->Field=$field;
        if(isset($options->Unix))
            $this->Unix=$options->Unix;

        if(isset($options->Value))
            $this->Value=$options->Value;
    }

    public function GetHtml($style = 'standard')
    {
        // TODO: Implement GetHtml() method.
    }

    public function GetType()
    {
        return 'datetime';
    }

    public function InternalGetDetails($base)
    {

        if($this->Unix==0||!is_numeric($this->Unix))
            return array();

        $base->NumericValue=$this->Unix;
        $base->DateValue=date('c',$this->Unix);
        $base->Value=$this->Value;

        return [$base];
    }


    public function IsEmpty()
    {
        return $this->Unix==0;
    }
}