<?php

namespace rnpagebuilder\PageGenerator\Core\QueryBuilder\EntryRetriever;

use rnpagebuilder\Utilities\Sanitizer;

class RowManager
{
    /** @var EntryRetriever */
    public $EntryRetriever;
    public $Rows;

    public $SerializedRows;
    public $CurrentRowIndex=-1;
    public $GlobalFormula=[];
    public function __construct($entryRetriever,$rows,$globalFormulas=[])
    {
        $this->SerializedRows=[];
        $this->Rows=$rows;
        $this->EntryRetriever=$entryRetriever;
        $this->GlobalFormula=$globalFormulas;
        $this->SetCurrentRow(0);
    }

    private function SetCurrentRow($rowIndex)
    {
        if(count($this->Rows)==0)
        {
            $this->CurrentRowIndex = -1;
            return;
        }

        if($rowIndex>=count($this->Rows)){
            $this->CurrentRowIndex=-1;
            return;
        }

        if($rowIndex<0)
            $rowIndex=0;

        if(!isset($this->SerializedRows[$rowIndex]))
        {
           $this->SerializedRows[$rowIndex]=new EntryRow($this,$this->Rows[$rowIndex]);
        }

        return $this->CurrentRowIndex=$rowIndex;



    }

    /**
     * @return EntryRow
     */
    public function GetCurrentRow(){
        if($this->CurrentRowIndex==-1)
            return null;
        return $this->SerializedRows[$this->CurrentRowIndex];
    }

    public function GetCurrentRowStringValue($fieldId, $fieldPath,$propertyPath=null,$defaultValue='')
    {
        $currentRow=$this->GetCurrentRow();
        return $currentRow->GetStringValue($fieldId,$fieldPath,$propertyPath,$defaultValue);
    }

    public function GetCurrentRowHtmlValue($fieldId, $fieldPath,$propertyPath=null,$defaultValue='')
    {
        $currentRow=$this->GetCurrentRow();
        return $currentRow->GetHtmlValue($fieldId,$fieldPath,$propertyPath,$defaultValue);
    }

    public function GetCurrentRowValue($fieldId, $fieldPath,$propertyPath=null,$defaultValue='')
    {
        $currentRow=$this->GetCurrentRow();
        return $currentRow->GetValue($fieldId,$fieldPath,$propertyPath,$defaultValue);
    }

    public function Reset()
    {
        $this->SetCurrentRow(0);
    }

    public function RowExist(){
        return $this->CurrentRowIndex!=-1;
    }

    public function RowCount()
    {
        return count($this->Rows);
    }

    public function GoNext()
    {
        $this->SetCurrentRow($this->CurrentRowIndex+1);
    }

    public function HasRows()
    {
        return count($this->Rows)>0;
    }

    public function GetCurrentRowFieldIsEmpty($fieldId)
    {
        $currentRow=$this->GetCurrentRow();
        return $currentRow->GetFieldIsEmpty($fieldId);
    }


}