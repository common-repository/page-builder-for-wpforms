<?php


namespace rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields;


use rnpagebuilder\Utilities\Sanitizer;

class SignatureFieldSettings extends FieldSettingsBase
{
    public function __construct()
    {
        parent::__construct();
        $this->RendererType='Signature';
        $this->IsPR=true;
    }

    public function GetType()
    {
        return 'Signature';
    }

    public function GetURL($value){
        $value= Sanitizer::GetStringValueFromPath($value,['value']);
        if($value=='')
            $value= Sanitizer::GetStringValueFromPath($value,['Value']);

        return $value;

    }

    public function ParseHTMLValue($value, $pathId = null)
    {
        if($this->GetURL($value)!='')
            return '<img src="'.$this->GetURL($value).'"/>';
        return '';
    }

}