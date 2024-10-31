<?php


namespace rnpagebuilder\core\Integration\Processors\Entry\EntryItems;


use rnpagebuilder\core\Integration\Processors\Entry\EntryItems\Core\EntryItemBase;

class HTMLEntryItem extends EntryItemBase
{
    public $HTML;

    public function GetText()
    {
        return $this->HTML;

    }

    public function SetHTML($value)
    {
        $this->HTML=$value;
        return $this;
    }

    protected function InternalGetObjectToSave()
    {
        return (object)array(
            'HTML'=>$this->HTML
        );
    }

    public function InitializeWithOptions($field,$options)
    {
        $this->Field=$field;
        if(isset($options->HTML))
            $this->HTML=$options->HTML;

    }

    public function GetHtml($style = 'standard')
    {
        // TODO: Implement GetHtml() method.
    }

    public function GetType()
    {
        return 'html';
    }

    public function IsEmpty()
    {
        return $this->HTML=='';
    }

    public function InternalGetDetails($base)
    {

        $base->Value=$this->HTML;

        return [$base];
    }


}