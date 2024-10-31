<?php


namespace rnpagebuilder\PageBuilder\DataSources\Core;


use rnpagebuilder\DTO\DataSourceBaseOptionsDTO;
use rnpagebuilder\PageBuilder\DataSources\FormDataSource\FormRow;
use rnpagebuilder\PageBuilder\PageBuilderGenerator;

abstract class DataSourceBase
{
    /** @var DataSourceBaseOptionsDTO */
    public $Options;
    /** @var PageBuilderGenerator */
    public $PageBuilder;
    /** @var ColumnBase[] */
    public $Columns;
    /** @var DataSourceRow */
    protected $CurrentRow;
    /** @var DataSourceRow[] */
    public $Rows=[];
    public $Count;

    public function __construct($pageBuilder, $dataSource)
    {
        $this->PageBuilder = $pageBuilder;
        $this->Options = $dataSource;
        $this->Columns = [];
        $this->InitializeColumns();
    }

    public function GetCurrentRow(){
        return $this->CurrentRow;
    }


    public function GetRowIndex(){
        for($i=0;$i<count($this->Rows);$i++)
        {
            if($this->Rows[$i]==$this->GetCurrentRow())
                return $i;
        }
        return -1;

    }
    public abstract function InitializeColumns();

    public abstract function CreateRows($limit, $skip);

    public abstract function GetFormConfig();

    public function GetCurrentRowStringValue($columnId)
    {
        if($this->CurrentRow==null)
            return '';

        return $this->CurrentRow->GetStringValue($columnId);

    }

    public function GetCurrentRowHTMLValue($columnId)
    {
        if($this->GetCurrentRow()==null)
            return '';
        return $this->GetCurrentRow()->GetHTMLValue($columnId);
    }
    public function GetFieldById($fieldId)
    {
        return null;
    }
    public function GetIterator($useCurrentIndex=false){
        $ds= new DataSourceIterator($this->PageBuilder,$this->Options,$this,$this->Rows);
        if($useCurrentIndex)
            $ds->SetCurrentIndex($this->GetRowIndex());

        return $ds;
    }


    public abstract function GetFieldLabel( $FieldId);

    public abstract function GetCurrentRowSimilarInput($FieldId);
}