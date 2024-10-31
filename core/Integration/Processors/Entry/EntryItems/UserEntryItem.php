<?php


namespace rnpagebuilder\core\Integration\Processors\Entry\EntryItems;


use rnpagebuilder\core\Integration\Processors\Entry\EntryItems\Core\EntryItemBase;

class UserEntryItem extends EntryItemBase
{
    public $UserId=0;


    public function GetText()
    {
        $user=\get_user_by('ID',$this->UserId);
        if($user==false)
            return '';
        return $user->user_nicename;

    }

    public function SetUserId($value)
    {
        $this->UserId=$value;
        return $this;
    }

    protected function InternalGetObjectToSave()
    {
        return (object)array(
            'UserId'=>$this->UserId
        );
    }

    public function InitializeWithOptions($field,$options)
    {
        $this->Field=$field;
        if(isset($options->UserId))
            $this->UserId=$options->UserId;

    }

    public function GetHtml($style = 'standard')
    {
        // TODO: Implement GetHtml() method.
    }

    public function GetType()
    {
        return 'user';
    }

    public function IsEmpty()
    {
        return $this->UserId==0;
    }

    public function InternalGetDetails($base)
    {
        $base->NumericValue=$this->UserId;

        return [$base];

    }


}