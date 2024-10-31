<?php


namespace rnpagebuilder\PageBuilder\DataSources\FormDataSource;


use rnpagebuilder\PageBuilder\DataSources\Core\DataSourceRow;

class FormRow extends DataSourceRow
{
    /** @var FormDataSource */
    public $DataSource;
    public $Data;
    public function __construct($dataSource,$data)
    {
        $this->DataSource=$dataSource;
        $this->Data=$data;
    }

    public function GetStringValue($columnId,$path=null)
    {
        $field=$this->DataSource->GetFieldById($columnId);
        if($field==null)
            return '';

        return $field->ParseStringValue($this->GetValue($columnId),$path);

    }

    public function GetValue($columnId)
    {
        if(!isset($this->Data->{'f_'.$columnId}))
            return null;

        return $this->Data->{'f_'.$columnId};
    }

    public function GetHTMLValue($columnId, $pathId=null)
    {
        $field=$this->DataSource->GetFieldById($columnId);
        if($field==null)
            return '';

        return $field->ParseHTMLValue($this->GetValue($columnId),$pathId);
    }
}