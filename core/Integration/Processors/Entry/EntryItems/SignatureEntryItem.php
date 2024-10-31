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

class SignatureEntryItem extends EntryItemBase
{
    public $Value;
    public function SetValue($value)
    {
        $this->Value=$value;
        return $this;
    }


    protected function InternalGetObjectToSave()
    {
        return (object)array(
            'Value'=>$this->Value
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
        return $this->Value;
    }

    public function GetType()
    {
        return 'signature';
    }

    public function IsEmpty()
    {
        return trim($this->Value)=='';
    }

    public function InternalGetDetails($base)
    {

        $base->Value=$this->Value;
        return [$base];

    }


}