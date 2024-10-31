<?php


namespace rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields;


use rnpagebuilder\Utilities\Sanitizer;

class ComposedFieldItem
{
    public $Id;
    public $Path;
    public $Label;
    public $AddCommaBefore=false;
    /** @var ComposedFieldRow */
    protected $Parent;

    /**
     * ComposedFieldItem constructor.
     * @param $Id
     * @param $Path
     */
    public function __construct($Parent,$Id='', $Path='',$Label='')
    {
        $this->Parent=$Parent;
        $this->Label=$Label;
        $this->Id = $Id;
        if(!\is_array($Path))
            $Path=[$Path];
        $this->Path = $Path;
    }

    public function SetParent($parent)
    {
        $this->Parent=$parent;
    }

    public function AddCommaBefore(){
        $this->AddCommaBefore=true;
        return $this;
    }


    public function InitializeFromOptions($options)
    {
        $this->Id=$options->Id;
        $this->Path=$options->Path;
        $this->Label=$options->Label;
        $this->AddCommaBefore=$options->AddCommaBefore;
    }

    public function CalculateWidth(){
        $items=count($this->Parent->Items);

        $width=round(100/$items*100)/100;
        return 'calc('.$width.'% - 2px)';
    }

    public function ParseStringValue($value){

        $value=Sanitizer::GetValueFromPath($value,'Value',[]);
        return Sanitizer::GetStringValueFromPath($value,$this->Path);
    }

}


