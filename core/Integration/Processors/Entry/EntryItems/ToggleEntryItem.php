<?php


namespace rnpagebuilder\core\Integration\Processors\Entry\EntryItems;


use rnpagebuilder\core\Integration\Processors\Entry\EntryItems\Core\EntryItemBase;

class ToggleEntryItem extends EntryItemBase
{
    public  $IsChecked=null;


    public function SetIsChecked($checked)
    {
        $this->IsChecked=$checked;
        return $this;
    }

    protected function InternalGetObjectToSave()
    {
        return (object)array(
            'IsChecked'=>$this->IsChecked
        );
    }

    public function InitializeWithOptions($field, $options)
    {
        $this->Field=$field;
        if(isset($options->IsChecked))
            $this->IsChecked=$options->IsChecked;
    }

    public function GetHtml($style = 'standard')
    {
        // TODO: Implement GetHtml() method.
    }

    public function GetText()
    {
        if($this->IsChecked)
            return 'True';
        return 'False';
    }

    public function GetType()
    {
        return 'toggle';
    }

    public function IsEmpty()
    {
        return $this->IsChecked===null;
    }

    public function InternalGetDetails($base)
    {
        $base->NumericValue=$this->IsChecked?1:0;

        return [$base];
    }


}