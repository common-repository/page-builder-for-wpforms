<?php

namespace rnpagebuilder\PageGenerator\Core\QueryBuilder\EntryRetriever;

use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\FieldSettingsBase;
use rnpagebuilder\PageGenerator\Core\QueryBuilder\FormulaParser;
use rnpagebuilder\Utilities\Sanitizer;

class EntryRow
{
    public $Data;
    public $Fields;
    /** @var RowManager */
    public $RowManager;
    public function __construct($rowManager,$data)
    {
        $this->RowManager=$rowManager;
        $this->Data=$data;
        $this->Fields=json_decode($data->fields);
    }

    /**
     * @param $fieldId
     * @return FieldSettingsBase
     */
    public function GetFieldSettingById($fieldId){
        foreach($this->RowManager->EntryRetriever->FieldSettings as $currentFieldSettings)
            if($currentFieldSettings->Id==$fieldId)
                return $currentFieldSettings;

        return null;
    }

    public function GetFieldRawValue($fieldId,$pathId='')
    {
        if($fieldId=='__formula')
        {
            $formulaParser=new FormulaParser();
            if(array_key_exists($pathId,$this->RowManager->GlobalFormula))
                return $formulaParser->Parse($this->RowManager->GlobalFormula[$pathId],$pathId,$this->RowManager->EntryRetriever);
            $property='formula_' . $pathId;
            return $formulaParser->Parse($this->Data->$property,$pathId,$this->RowManager->EntryRetriever);
        }
        if($fieldId=='__entryId'||$fieldId=='__entryId_Value')
            return $this->Data->entry_id;
        if($fieldId=='__date')
        {
            $dateToUse=$this->Data->date;
            $unix=strtotime($dateToUse);
            return (object)[
                "name"=>__("Creation date"),
                'value'=>$dateToUse,
                'type'=>'date-time',
                'date'=>date('d/m/Y',$unix),
                'time'=>date('g:i a'),
                'unix'=>$unix
            ];
        }
        foreach($this->Fields as $rowFieldId=>$value)
        {
            if($rowFieldId==$fieldId)
                return $value;
        }

        return null;
    }

    public function GetValue($fieldId, $fieldPath=null,$propertyPath=null,$defaultValue=null,$format='html')
    {
        $fieldValue=$this->GetFieldRawValue($fieldId,$fieldPath);
        $fieldSettings=null;
        if($fieldPath==null&&is_numeric($fieldId)&&$propertyPath==null)
        {
            $fieldPath='Value';
        }
        if($fieldPath!=null&&$fieldId!='__formula')
        {
            $fieldSettings = $this->GetFieldSettingById($fieldId);
            if ($fieldSettings == null)
                return $defaultValue;

            if($format=='string')
                return $fieldSettings->ParseStringValue($fieldValue,$fieldPath);

            $fieldValue=$fieldSettings->ParseValue($fieldValue,$fieldPath);

        }

        if($propertyPath==null)
            return $fieldValue;

        return Sanitizer::GetValueFromPath($fieldValue,$propertyPath,$defaultValue);
    }


    public function GetStringValue($fieldId, $fieldPath=null,$propertyPath=null,$defaultValue='')
    {
        $value=$this->GetValue($fieldId,$fieldPath,$propertyPath,null,'string');
        if($value==null)
            return $defaultValue;

        return Sanitizer::SanitizeString($value);
    }

    public function GetHtmlValue($fieldId, $fieldPath=null,$propertyPath=null,$defaultValue='')
    {


        $fieldValue=$this->GetFieldRawValue($fieldId,$fieldPath);
        if($fieldId=='__formula')
            return $fieldValue;

        if($fieldPath!=null)
        {
            $fieldSettings = $this->GetFieldSettingById($fieldId);
            if ($fieldSettings == null)
                return $defaultValue;

            $fieldValue=$fieldSettings->ParseHTMLValue($fieldValue,$fieldPath);
        }

        if($propertyPath==null)
            return $fieldValue;

        return Sanitizer::GetValueFromPath($fieldValue,$propertyPath,$defaultValue);
    }

    public function MoveNextRow(){
        $this->RowManager->GoNext();
    }

    public function GetFieldIsEmpty($fieldId)
    {
        return $this->GetStringValue($fieldId)=='';
    }

}