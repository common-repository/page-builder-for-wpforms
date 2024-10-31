<?php


namespace rnpagebuilder\PageGenerator\Core\QueryBuilder\Comparison;


class ListFixedValueComparison extends FixedValueComparison
{
    public $DetailTableName;
    public $FieldId;
    public function __construct($DetailTableName,$FieldId,$Table, $Column, $Comparison, $Value, $ComparisonFormatter = null)
    {
        $this->DetailTableName=$DetailTableName;
        $this->FieldId=$FieldId;
        parent::__construct($Table, $Column, $Comparison, $Value, $ComparisonFormatter);
    }


    public function CreateComparison()
    {
        $value=$this->Value;
        $escapedValuesArray=array();
        global $wpdb;
        if(\is_array($value))
        {
            foreach($value as $currentValue)
            {
                $escapedValuesArray[]=$wpdb->prepare('%s',$currentValue);
            }
        }



        $leftSide=$this->Table.'.'.$this->Column;
        global $wpdb;

        switch ($this->Comparison)
        {
            case 'Contains':
                if(count($escapedValuesArray)==0)
                    return ' true ';
                return $leftSide.' in ('.\implode(',',$escapedValuesArray).')';
            case 'NotContains':
                if(count($escapedValuesArray)==0)
                    return ' true ';
                return "( not exists(select 1 from ".$this->DetailTableName.' aux where aux.entry_id='.$this->Table.'.entry_id and aux.field_id='.$wpdb->prepare('%s',$this->FieldId).
                    ' and aux.value in ('.\implode(',',$escapedValuesArray).')))';
            case 'IsEmpty':
                return $leftSide .' is null ';
            case 'IsNotEmpty':
                return $leftSide .' is not null ';

        }

        return parent::CreateComparisonString($leftSide,$this->ComparisonFormatter->Format($this->Value));
    }
}