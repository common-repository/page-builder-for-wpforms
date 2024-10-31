<?php


namespace rnpagebuilder\PageBuilder\DataSources\Core;


use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\FieldSettingsBase;

class DataSourceIterator extends DataSourceBase implements \Countable
{
    /** @var DataSourceRow[] */
    public $Rows;
    private $Index;
    /** @var DataSourceBase */
    public $DataSource;

    public function __construct($pageBuilder, $dataSourceOptions,$dataSource,$rows)
    {
        parent::__construct($pageBuilder, $dataSourceOptions);
        $this->Rows=$rows;
        $this->DataSource=$dataSource;
        $this->Index=-1;
    }


    /**
     * @return DataSourceRow
     */
    public function GetNextRow()
    {
        $this->Index++;
        return $this->GetCurrentRow();
    }

    public function GetCurrentIndex(){
        return $this->Index;
    }

    public function SetCurrentIndex($index){
        return $this->Index=$index;
    }
    /**
     * @return DataSourceRow
     */
    public function GetCurrentRow(){
        if($this->Index+1>count($this->Rows))
            return null;

        return $this->Rows[$this->Index];
    }

    public function InitializeColumns()
    {

    }

    public function CreateRows($limit, $skip)
    {

    }
    public function Reset(){
        $this->Index=-1;
    }

    public function GetFormConfig()
    {

    }

    public function GetFieldLabel( $FieldId)
    {
        return $this->DataSource->GetFieldLabel($FieldId);
    }

    public function GetCurrentRowSimilarInput( $FieldId)
    {
        if($this->GetCurrentRow()==null)
            return '';
        /** @var FieldSettingsBase $field */
        $field=$this->DataSource->GetFieldById($FieldId);
        if($field==null)
            return '';

        return $field->ParseSimilarInput($this->PageBuilder->Twig,$this->GetCurrentRow()->GetValue($FieldId));
    }

    public function GetFieldById($fieldId)
    {
        return $this->DataSource->GetFieldById($fieldId);
    }

    public function GetCurrentRowValue($columnId)
    {
        return $this->GetCurrentRow()->GetValue($columnId);
    }

    public function GetCurrentRowStringValue($columnId)
    {
        if($this->GetCurrentRow()==null)
            return '';

        return $this->GetCurrentRow()->GetStringValue($columnId);
    }

    public function count()
    {
        return count($this->Rows);
    }
}



