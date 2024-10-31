<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/21/2019
 * Time: 4:25 AM
 */

namespace rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields;

use rnpagebuilder\DTO\ManuallyCreated\DataSection;
use rnpagebuilder\Utilities\Sanitizer;
use Twig\Environment;
use Twig\Markup;

abstract class FieldSettingsBase
{
    public function __construct()
    {
        $this->RendererType='Text';
        $this->IsPR=false;
    }

    public $IsPR;
    public $Id;
    public $Label;
    public $Type;
    public $SubType;
    public $RawSettings;
    public $RendererType='';
    public function Initialize($FieldId,$Label,$SubType,$rawSettings=null){
        $this->Id=$FieldId;
        $this->Label=$Label;
        $this->Type=$this->GetType();
        $this->SubType=$SubType;
        $this->RawSettings=$rawSettings;

        return $this;
    }

    public function GetAggregationPath(){
        return '';
    }

    public function GetValue($options,$path=[],$defaultValue=null)
    {
        $currentValue=$options;
        if(!\is_array($path))
            $path=[$path];

        while(($value=\array_shift($path))!=null)
        {
            if(!isset($options->$value))
                return $defaultValue;

            $currentValue=$options->$value;
        }

        return $currentValue;
    }

    public function GetStringValue($options,$path=[],$defaultValue='')
    {
        $value=$this->GetValue($options,$path,$defaultValue);

        if(!\is_string($value))
            return $defaultValue;

        $value=\strval($value);

        if(trim($value)=='')
            return $defaultValue;

        return $value;
    }

    public function GetBoolValue($options,$path=[],$defaultValue=false)
    {
        $value=$this->GetValue($options,$path,$defaultValue);
        if($value==false)
            return false;

        return true;
    }

    public function InitializeFromOptions($options)
    {
        $this->Id=$options->Id;
        $this->Label=$options->Label;
        $this->Type=$options->Type;
        $this->SubType=$options->SubType;
        $this->RawSettings=$options->RawSettings;
        $this->RendererType=$options->RendererType;
    }

    public function SetStringProperty($propertyName,$options,$path,$defaultValue='')
    {
        $this->$propertyName=$this->GetStringValue($options,$path,$defaultValue);

    }

    public function ParseStringValue($value,$pathId=null)
    {
        return Sanitizer::SanitizeString($this->ParseValue($value,$pathId));
    }

    public function ParseValue($value,$pathId=null)
    {
        if($pathId==null)
            $path=['Value'];
        else
        {
            $pathFound=false;
            foreach ($this->GetDataSections('Display') as $currentSection)
            {
                if($currentSection->PathId==$pathId)
                {
                    $path = $currentSection->Path;
                    $pathFound=true;
                }

            }

            if(!$pathFound)
                return '';
        }
        return Sanitizer::GetValueFromPath($value,$path);
    }

    public function ParseHTMLValue($value,$pathId=null)
    {
        return $this->ParseValue($value,$pathId);
    }


    public function GetTemplatePath()
    {
        return 'Fields/'.$this->RendererType.'.twig';
    }

    public function ParseSimilarInput($twig,$value){
        return new Markup($twig->render($this->GetTemplatePath(),['FieldSettings'=>$this,'Value'=>$value]),'UTF-8');
    }


    public abstract function GetType();




    /**
     * @param string $mode
     * @return DataSection[]
     */
    public function GetDataSections($mode='Filter'){
        $sections=[];
        $sections[]=new DataSection($this->Id,$this->Label,'Value',['value']);
        return $sections;
    }
}