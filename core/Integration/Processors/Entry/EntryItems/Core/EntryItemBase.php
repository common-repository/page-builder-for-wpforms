<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/22/2019
 * Time: 5:49 AM
 */

namespace rnpagebuilder\core\Integration\Processors\Entry\EntryItems\Core;


use phpDocumentor\Reflection\Types\Boolean;
use rnpagebuilder\core\Integration\Processors\Entry\HTMLFormatters\PHPFormatterBase;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\FieldSettingsBase;

abstract class EntryItemBase
{
    /** @var FieldSettingsBase */
    public $Field;

    public function __construct()
    {

    }

    public function Initialize($field)
    {
        $this->Field=$field;
        return $this;
    }

    public function GetObjectToSave(){
        $data=$this->InternalGetObjectToSave();
        $data->_fieldId=$this->Field->Id;
        return $data;
    }

    public abstract function GetText();

    public function GetNumber(){
        return \floatval($this->GetText());
    }

    protected abstract function InternalGetObjectToSave();
    public abstract function InitializeWithOptions($field,$options);

    /**
     * @param string $style
     * @return PHPFormatterBase
     */
    public abstract function GetHtml($style='standard');

    /**
     * @return string
     */
    public abstract function GetType();

    /**
     * @return Boolean
     */
    public abstract function IsEmpty();

    public function Contains($value)
    {
        if(!\is_array($value))
            $value=[$value];

        foreach($value as $currentValue)
            if($currentValue==$this->GetText())
                return true;
        return false;
    }

    public function ProcessFieldMethod($methodName)
    {
        return '';
    }

    /**
     * @param $base EntryDetailItem
     * @return mixed
     */
    public abstract function InternalGetDetails($base);


    /**
     * @param $entryId
     * @param $index
     * @return EntryDetailItem[]
     */
    public function GetDetails($entryId,$formId,$index)
    {
        if($this->IsEmpty())
            return array();

        $details=new EntryDetailItem();
        $details->Type=$this->GetType();
        $details->EntryId=$entryId;
        $details->FormId=$formId;
        $details->UniqId=$index;
        $details->FieldId=$this->Field->Id;
        $details->OriginalType=$this->Field->SubType;

        return $this->InternalGetDetails($details);
    }

    public function SanitizeRawValue($value){
        return $value;

    }


}