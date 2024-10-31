<?php


namespace rnpagebuilder\PageBuilderOld\QueryBuilder;


use rnpagebuilder\core\db\core\DBManager;
use rnpagebuilder\core\Loader;
use rnpagebuilder\core\Utils\ArrayUtils;
use rnpagebuilder\DTO\SortItemOptionsDTO;
use rnpagebuilder\PageBuilderOld\QueryBuilder\Comparison\ColumnComparison;
use rnpagebuilder\PageBuilderOld\QueryBuilder\Comparison\FixedValueComparison;
use rnpagebuilder\PageBuilderOld\QueryBuilder\Filters\FilterGroup;
use rnpagebuilder\PageBuilderOld\QueryBuilder\QueryElement\Dependency;
use rnpagebuilder\PageBuilderOld\QueryBuilder\QueryElement\QueryElement;

class QueryBuilder
{
    public $Level;
    /** @var QueryElement[] */
    public $Elements;
    public $RootTable;
    public $RootID;
    /** @var FilterGroup[] */
    public $RootConditions;
    /** @var Dependency[] */
    public $Dependencies;
    /** @var FilterGroup[] */
    public $Filters;
    public $LastQuery='';

    /** @var string[] */
    public $Columns;
    public $NegateFilter;
    /** @var Loader */
    public $Loader;
    /** @var SortItemOptionsDTO[] */
    public $SortItems;
    /**
     * QueryBuilder constructor.
     * @param $RootTable
     * @param $RootID
     * @param $rootConditions FilterGroup[]
     */
    public function __construct($Loader,$RootTable,$RootID,$rootConditions=[])
    {
        $this->SortItems=[];
        $this->Loader=$Loader;
        if($rootConditions==null)
            $rootConditions=[];
        $this->RootTable=$RootTable;
        $this->RootID=$RootID;
        $this->RootConditions=$rootConditions;
        $this->Filters=[];
        $this->NegateFilter=false;
        $this->Elements=[];
        $this->Dependencies=[];
    }

    public function SetNegateFilter(){
        $this->NegateFilter=true;
    }


    /**
     * @param $queryElement QueryElement
     */
    public function AddQueryElement($queryElement)
    {
        $this->Elements[]=$queryElement;
    }

    public function Execute($limit=0,$skip=0)
    {

        $limitFilter='';
        if($limit>0)
        {
            $limitFilter=' limit ';

            if($skip>0)
                $limitFilter.=$skip.' , ';

            $limitFilter.=$limit;
        }
        $sortSection=$this->GenerateSorts();
        $this->GenerateColumns();
        $filters=$this->GenerateFilterString();

        $query=$this->CreateQueryString();

        $query=$query.' '.$filters.$sortSection.$limitFilter;
        $this->LastQuery= $query;
        return $query;

    }

    public function GetCount()
    {

        $limitFilter='';
        $filters=$this->GenerateFilterString();
        $query=$this->CreateQueryString(true);

        $query=$query.' '.$filters;
        $this->LastQuery= $query;

        $dbmanager=new DBManager(true);
        return intval($dbmanager->GetVar($query));

    }

    public function GetRows($limit=0,$skip=0){
        $dbmanager=new DBManager(true);
        $result=$dbmanager->GetResults($this->Execute($limit,$skip));
        return $result;
    }


    private function GenerateColumns()
    {
        $this->Columns=[];
        foreach($this->Elements as $element)
        {
            foreach($element->Dependencies as $dependency)
            {
                if($this->HasDependency($dependency->Id))
                    continue;
                $this->Dependencies[]=$dependency;
            }
            foreach($element->Columns as $column)
            {
                $column=$column->CreateColumn();
                if(ArrayUtils::Find($this->Columns,function ($item)use($column){return $item==$column;})==null)
                    $this->Columns[]=$column;
            }

        }

        if(count($this->Columns)==0)
            $this->Columns[]='1';

    }

    public function HasDependency($dependency)
    {
        return ArrayUtils::Find($this->Dependencies,function ($item)use($dependency){
            return $dependency==$item->Id;
        })!=null;

    }




    private function GenerateFilterString()
    {
        $filters='';
        if(count($this->RootConditions)>0){
            foreach ($this->RootConditions as $filterGroup)
            {
                $filters.=' and '.$filterGroup->CreateGroupString();
            }
        }

        $andFilters='';
        $orFilters='';

        foreach($this->Filters as $filterGroup)
        {
            if($filterGroup->JoinType=='and')
            {
                if($andFilters!='')
                    $andFilters=$andFilters.' and ';
                $andFilters.=$filterGroup->CreateGroupString();
            }
        }
        if($andFilters!='')
            $andFilters=' and ('.$andFilters.')';

        foreach($this->Filters as $filterGroup)
        {
            if($filterGroup->JoinType=='or')
            {
                if($orFilters!='')
                    $orFilters=$orFilters.' or ';
                $orFilters.=$filterGroup->CreateGroupString();
            }
        }
        if($orFilters!='')
            $orFilters=' and ('.$orFilters.')';


        $filters=$andFilters.$orFilters;

        if($filters!='')
        {
            if($this->NegateFilter)
                $filters=' and not(1=1 '.$filters.')';

            $filters=' where 1=1'.$filters;
        }

        return $filters;

    }

    private function CreateQueryString($isCountString=false)
    {
        if($isCountString)
            $query=' select count(*) ';
        else
            $query=' select distinct '.\implode(', ',$this->Columns);
        $query.=' from '.$this->RootTable.' '.$this->RootID;

        foreach($this->Dependencies as $dependency)
        {
            $query.=$dependency->CreateJoin();
        }

        return $query;

    }


    /**
     * @param $dependency Dependency
     */
    public function AddDependency($dependency)
    {
        if(ArrayUtils::Some($this->Dependencies,function ($item)use($dependency){
            return $dependency->Id==$item->Id;
        }))
            return;

        $this->Dependencies[]=$dependency;
    }

    private function GenerateSorts()
    {
        $sortText='';
        foreach($this->SortItems as $currentSort)
        {
            $sortId=$currentSort->FieldId.'_'.$currentSort->PathId;
            $dependency=new Dependency($this->Loader->RECORDS_DETAIL_TABLE,$sortId);
            $this->AddDependency($dependency);
            $dependency->AddComparison(new ColumnComparison('ROOT','entry_id','Equal',$sortId,'entry_id'));
            $dependency->Comparisons[]=new FixedValueComparison($sortId,'field_id','Equal',$currentSort->FieldId);


            if($currentSort->PathId==''||$currentSort->PathId=='Value')
                $dependency->Comparisons[]=new FixedValueComparison($sortId,'path_id','IsNull',null);
            else
                $dependency->Comparisons[]=new FixedValueComparison($sortId,'path_id','Equal',$currentSort->PathId);


            $column=new QueryElement();
            $column->AddColumn2($sortId,'value',$sortId);
            $this->AddQueryElement($column);

            if($sortText!='')
                $sortText.=",";

            $sortText.=$sortId.' ' .$currentSort->Orientation;
        }
        if($sortText!='')
            $sortText=' order by '.$sortText.' ';
        return $sortText;
    }


}