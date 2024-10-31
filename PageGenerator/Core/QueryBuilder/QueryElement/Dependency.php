<?php


namespace rnpagebuilder\PageGenerator\Core\QueryBuilder\QueryElement;


use rnpagebuilder\PageGenerator\Core\QueryBuilder\Comparison\ComparisonBase;

class Dependency
{
    public $TableName;
    public $Id;

    /** @var ComparisonBase[] */
    public $Comparisons;

    public function __construct($tableName,$id)
    {
        $this->TableName=$tableName;
        $this->Id=$id;
    }

    public function AddComparison($comparison)
    {
        $this->Comparisons[]=$comparison;
        return $this;
    }

    public function CreateJoin()
    {
        $join=' left join ';
        $join.=$this->TableName.' '.$this->Id;
        $join.=' on ';

       $join.=$this->CreateOnClause();

        return $join;
    }
    public function CreateOnClause(){
        $join='';
        for($i=0;$i<count($this->Comparisons);$i++)
        {
            if($i>0)
                $join.=' and ';
            $join.=$this->Comparisons[$i]->CreateComparison();
        }

        return $join;
    }

    public function CreateSubQuery()
    {
        $subQuery='select * from '.$this->TableName.' '.$this->Id.' where ';

        for($i=0;$i<count($this->Comparisons);$i++)
        {
            if($i>0)
                $subQuery.=' and ';
            $subQuery.=$this->Comparisons[$i]->CreateComparison();
        }

        return $subQuery;

    }


}