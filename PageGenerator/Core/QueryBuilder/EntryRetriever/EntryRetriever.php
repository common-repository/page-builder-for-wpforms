<?php

namespace rnpagebuilder\PageGenerator\Core\QueryBuilder\EntryRetriever;

use rnpagebuilder\core\db\FormRepository;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\FieldSettingsBase;
use rnpagebuilder\core\Loader;
use rnpagebuilder\DTO\ConditionGroupOptionsDTO;
use rnpagebuilder\PageGenerator\Core\PageGenerator;
use rnpagebuilder\PageGenerator\Core\QueryBuilder\QueryBuilder;
use rnpagebuilder\Utilities\Sanitizer;

class EntryRetriever
{
    private $TotalRows=null;
    /** @var QueryBuilder */
    public $QueryBuilder;
    public $Rows;
    /** @var Loader */
    public $Loader;
    /** @var PageGenerator */
    public $PageGenerator;
    /** @var FieldSettingsBase[] */
    public $FieldSettings;
    public $CurrentRow;
    /** @var RowManager */
    public $RowManager;
    public $GlobalFormulas;
    /**
     * @param $pageGenerator PageGenerator
     */
    public function __construct($pageGenerator,$queryBuilder)
    {
        $this->Rows=[];
        $this->RowManager=new RowManager($this,[]);
        if($pageGenerator!=null)
        {
            $this->Loader=$pageGenerator->Loader;
            $this->PageGenerator=$pageGenerator;
            $this->QueryBuilder=$queryBuilder;

            $formRepository=new FormRepository($this->Loader);
            $this->FieldSettings=$formRepository->GetFieldConfig($this->PageGenerator->Options->FormId);
            $queryBuilder->FieldSettings=$this->FieldSettings;
        }

    }

    public function GetCurrentRowStringValue($fieldId, $fieldPath='')
    {
        return $this->RowManager->GetCurrentRowStringValue($fieldId,$fieldPath);
    }

    public function GetCurrentRowHtmlValue($fieldId, $fieldPath='')
    {
        return $this->RowManager->GetCurrentRowHtmlValue($fieldId,$fieldPath);
    }

    public function GetCurrentEntryId(){
        return  Sanitizer::GetStringValueFromPath($this->RowManager->GetCurrentRow(),['Data','entry_id']);

    }

    public function GetCurrentRow(){
        return $this->RowManager->GetCurrentRow();
    }
    public function GetCurrentRowValue($fieldId, $fieldPath='',$propertyPath=null,$defaultValue='')
    {
        return $this->RowManager->GetCurrentRowValue($fieldId,$fieldPath,$propertyPath,$defaultValue);
    }


    /**
     * @param $fieldId
     * @return FieldSettingsBase|null
     */
    public function GetFieldById($fieldId)
    {
        foreach($this->FieldSettings as $currentField)
        {
            if($currentField->Id==$fieldId)
                return $currentField;
        }
        return null;
    }

    public function ExecuteQuery($limit=0,$skip=0)
    {
        $globalFormulas=[];
        $this->Rows=$this->QueryBuilder->GetRows($limit,$skip,$globalFormulas);
        $this->RowManager=new RowManager($this,$this->Rows,$globalFormulas);

    }

    public function GetTotalRows()
    {
        if($this->TotalRows==null)
            $this->TotalRows=$this->QueryBuilder->GetCount();

        return $this->TotalRows;
    }

    public function GetCurrentRowFieldIsEmpty($fieldId)
    {
        return $this->RowManager->GetCurrentRowFieldIsEmpty($fieldId);
    }



}