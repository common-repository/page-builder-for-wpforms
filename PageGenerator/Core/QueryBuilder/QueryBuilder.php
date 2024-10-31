<?php


namespace rnpagebuilder\PageGenerator\Core\QueryBuilder;


use rnpagebuilder\core\db\core\DBManager;
use rnpagebuilder\core\Exception\ExceptionSeverity;
use rnpagebuilder\core\Exception\FriendlyException;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\FieldSettingsBase;
use rnpagebuilder\core\Loader;
use rnpagebuilder\core\Utils\ArrayUtils;
use rnpagebuilder\DTO\ConditionLineOptionsDTO;
use rnpagebuilder\DTO\FormulaOptionsDTO;
use rnpagebuilder\DTO\SortItemOptionsDTO;
use rnpagebuilder\PageGenerator\Core\PageGenerator;
use rnpagebuilder\PageGenerator\Core\QueryBuilder\Comparison\ColumnComparison;
use rnpagebuilder\PageGenerator\Core\QueryBuilder\Comparison\ComparisonFormatter\NumericComparisonFormatter;
use rnpagebuilder\PageGenerator\Core\QueryBuilder\Comparison\DateFixedValueComparison;
use rnpagebuilder\PageGenerator\Core\QueryBuilder\Comparison\EntryIdValueComparison;
use rnpagebuilder\PageGenerator\Core\QueryBuilder\Comparison\FixedValueComparison;
use rnpagebuilder\PageGenerator\Core\QueryBuilder\Comparison\ListFixedValueComparison;
use rnpagebuilder\PageGenerator\Core\QueryBuilder\Comparison\UserValueComparison;
use rnpagebuilder\PageGenerator\Core\QueryBuilder\Filters\FilterGroup;
use rnpagebuilder\PageGenerator\Core\QueryBuilder\Filters\FilterLineBase;
use rnpagebuilder\PageGenerator\Core\QueryBuilder\QueryElement\Dependency;
use rnpagebuilder\PageGenerator\Core\QueryBuilder\QueryElement\QueryElement;
use rnpagebuilder\pr\PageGenerator\Blocks\SearchBar\SearchBarBlock;
use rnpagebuilder\Utilities\Sanitizer;

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
    /** @var FormulaOptionsDTO[] */
    public $Formulas;
    /** @var PageGenerator */
    public $PageGenerator;
    public $FormId;
    public $GlobalFormulas=[];
    /** @var FieldSettingsBase[] */
    public $FieldSettings;
    /**
     * QueryBuilder constructor.
     * @param $RootTable
     * @param $RootID
     * @param $rootConditions FilterGroup[]
     */
    public function __construct($Loader,$RootTable,$RootID,$rootConditions=[],$FormId='',$pageGenerator=null)
    {
        $this->PageGenerator=$pageGenerator;
        $this->FormId=$FormId;
        $this->FieldSettings=[];
        $this->Formulas=[];
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
     * @param $filterGroup
     * @param $conditionLine ConditionLineOptionsDTO
     * @return void
     * @throws FriendlyException
     */
    public function GenerateQueryElements($filterGroup,$conditionLine)
    {
        $tableId=null;
        $filterLine=null;
        $column=null;
        switch ($conditionLine->FieldId)
        {
            case '__userId':
                $filterLine=new FilterLineBase($filterGroup);
                $filterGroup->FilterLines[]=$filterLine;
                $tableId=$this->RootID;
                $column='user_id';
                break;
            case '__entryId':
                $filterLine=new FilterLineBase($filterGroup);
                $filterGroup->FilterLines[]=$filterLine;
                $tableId=$this->RootID;
                $column='entry_id';
                break;

            case '__date':
                $filterLine=new FilterLineBase($filterGroup);
                $filterGroup->FilterLines[]=$filterLine;
                $tableId=$this->RootID;
                $column='date';
                break;
            default:
                $tableId=$this->RootID.'_'.$conditionLine->FieldId.'_'.$conditionLine->PathId;
                $column='value';

                if($conditionLine->Column!='')
                    $column=preg_replace("/[^A-Za-z0-9 ]/", '', $conditionLine->Column);

                $dependency=new Dependency($this->Loader->RECORDS_DETAIL_TABLE,$tableId);
                $dependency->Comparisons[]=new ColumnComparison($this->RootID,'entry_id','Equal',$tableId,'entry_id');
                $dependency->Comparisons[]=new FixedValueComparison($tableId,'field_id','Equal',$conditionLine->FieldId);

                $this->AddDependency($dependency);
                $filterLine=new FilterLineBase($filterGroup);
                $filterGroup->FilterLines[]=$filterLine;




                if($conditionLine->PathId=='Value'||$conditionLine->PathId=='')
                    $dependency->Comparisons[]=new FixedValueComparison($tableId,'path_id','IsNull',null);
                else
                    $dependency->Comparisons[]=new FixedValueComparison($tableId,'path_id','Equal',$conditionLine->PathId);

        }


        if($conditionLine->HasMapping)
        {
            if (Sanitizer::GetStringValueFromPath($conditionLine, ['Value', 'Type']) == 'SearchField')
            {
                /** @var SearchBarBlock[] $searchBlock */
                $searchBlock = $this->PageGenerator->GetBlocksByType('SearchBar');
                if (count($searchBlock) == 0)
                {
                    $filterGroup->FilterLines = [];
                    return;
                }

                $searchCondition=$searchBlock[0]->GetSearchFieldCondition(Sanitizer::GetStringValueFromPath($conditionLine, ['Value', 'Id']));
                if($searchCondition==null||count($searchCondition->ConditionLines)==0)
                {
                    $filterGroup->FilterLines=[];
                    return;
                }
                $id = $searchBlock[0]->Options->Id . '_' . Sanitizer::GetStringValueFromPath($conditionLine, ['Value', 'Id']);
                $conditionLine=(new ConditionLineOptionsDTO())->Merge($conditionLine);
                $conditionLine->Value = $searchCondition->ConditionLines[0]->Value;

            } else
            {
                $mappingFieldId = preg_replace("/[^A-Za-z0-9 ]/", '', $conditionLine->Value->Id);
                $mappingPathId = preg_replace("/[^A-Za-z0-9 ]/", '', $conditionLine->Value->Path);
                $filterLine->Filter = new ColumnComparison($tableId, $column, $conditionLine->Comparison, 'ROOT_' . $mappingFieldId . '_' . $mappingPathId, 'Value');
                return;
            }
        }


        switch($conditionLine->SubType)
        {
            case 'Text':
            case 'Composed':
                $filterLine->Filter = new FixedValueComparison($tableId, $column, $conditionLine->Comparison, $conditionLine->Value);
                break;
            case 'Multiple':
                $filterLine->Filter = new ListFixedValueComparison($this->Loader->RECORDS_DETAIL_TABLE, $conditionLine->FieldId, $tableId, $column, $conditionLine->Comparison, $conditionLine->Value);
                break;
            case 'CurrencyMultiple':
                $filterLine->Filter = new ListFixedValueComparison($this->Loader->RECORDS_DETAIL_TABLE, $conditionLine->FieldId, $tableId, $column, $conditionLine->Comparison, $conditionLine->Value, new NumericComparisonFormatter());
                break;
            case 'Number':
            case 'Currency':
            case 'Time':
                if ($column == 'value')
                    $column = 'numericvalue';
                $filterLine->Filter = new FixedValueComparison($tableId, $column, $conditionLine->Comparison, $conditionLine->Value, new NumericComparisonFormatter());
                break;
            case 'DateTime':
            case 'Date':
                $filterLine->Filter = new DateFixedValueComparison($tableId, $column, $conditionLine->Comparison, $conditionLine->Value, null, $conditionLine->AdditionalOptions);
                break;
            case 'User':
                $filterLine->Filter = new UserValueComparison($filterLine, $tableId, $column, $conditionLine->Comparison, $conditionLine->Value);
                break;
            case 'EntryId':
                $filterLine->Filter = new EntryIdValueComparison($filterLine, $tableId, $column, $conditionLine->Comparison, $conditionLine->Value);
                break;
            default:
                throw new FriendlyException('Invalid condition type ' . $conditionLine->SubType . ', please check the data source filters and make sure they are correct', '', ExceptionSeverity::$FATAL);
        }


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
        $this->AddFormulaDependencies();

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

    public function GetRows($limit=0,$skip=0,&$globalFormulas=[]){
        $this->GlobalFormulas=[];
        $dbmanager=new DBManager(true);
        $result=$dbmanager->GetResults($this->Execute($limit,$skip));
        foreach($this->GlobalFormulas as $key=>$currentGlobalFormula)
            $globalFormulas[$key]=$dbmanager->GetVar($currentGlobalFormula);

        return $result;
    }


    protected function GenerateColumns()
    {
        $this->Columns=['fields',$this->RootID.'.entry_id',$this->RootID.'.date'];
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

        }
        $filters=' where '.$this->RootID.'.form_id='.intval($this->FormId).$filters;

        return $filters;

    }

    protected function CreateQueryString($isCountString=false)
    {
        $query=$this->GenerateColumnsSectionQuery($isCountString);
        $query.=' from '.$this->RootTable.' '.$this->RootID;

        foreach($this->Dependencies as $dependency)
        {
            $query.=$dependency->CreateJoin();
        }

        return $query;

    }

    protected function GenerateColumnsSectionQuery($isCountString=false)
    {
        if($isCountString)
            return ' select count(*) ';
        else
            return ' select distinct '.\implode(', ',$this->Columns).$this->GenerateFormulas();
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
            $column=new QueryElement();
            switch ($currentSort->FieldId)
            {
                case '__entryId':
                    $sortId=$this->RootID.'.entry_id';
                    $column->AddColumn2($this->RootID,'entry_id','entry_id');
                    break;
                case '__date':
                    $sortId=$this->RootID.'.date';
                    $column->AddColumn2($this->RootID,'date','date');
                    break;
                default:
                    $sortId=$currentSort->FieldId.'_'.$currentSort->PathId;
                    $dependency=new Dependency($this->Loader->RECORDS_DETAIL_TABLE,$sortId);
                    $this->AddDependency($dependency);
                    $dependency->AddComparison(new ColumnComparison($this->RootID,'entry_id','Equal',$sortId,'entry_id'));
                    $dependency->Comparisons[]=new FixedValueComparison($sortId,'field_id','Equal',$currentSort->FieldId);


                    if($currentSort->PathId==''||$currentSort->PathId=='Value')
                        $dependency->Comparisons[]=new FixedValueComparison($sortId,'path_id','IsNull',null);
                    else
                        $dependency->Comparisons[]=new FixedValueComparison($sortId,'path_id','Equal',$currentSort->PathId);


                    $column=new QueryElement();
                    $column->AddColumn2($sortId,'value',$sortId);

            }

            $this->AddQueryElement($column);



            if($sortText!='')
                $sortText.=",";

            $sortText.=$sortId.' ' .$currentSort->Orientation;
        }
        if($sortText!='')
            $sortText=' order by '.$sortText.' ';
        return $sortText;
    }

    public function AddFormula($currentFormula)
    {
        $this->Formulas[]=$currentFormula;
    }

    private function GenerateFormulas()
    {
        $formulas='';
        $this->GlobalFormulas=[];
        foreach($this->Formulas as $currentFormula)
        {
            $formulaRootTable='Formula_'.$currentFormula->Id;
            $query=new AggregationBuilder($this->Loader,$this->Loader->GetRecordsTableName(),$formulaRootTable,[],$currentFormula,$this->FormId,$this->PageGenerator);
            $isGlobal=true;
            foreach($currentFormula->Condition->ConditionGroups as $currentCondition)
            {
                $filterGroup=new FilterGroup($query);

                foreach($currentCondition->ConditionLines as $conditionLine)
                {
                    if($conditionLine->HasMapping&&Sanitizer::GetStringValueFromPath($conditionLine,['Value','Type'])!='SearchField')
                        $isGlobal=false;
                    $query->GenerateQueryElements($filterGroup, $conditionLine);
                }

                if(count($filterGroup->FilterLines)>0)
                    $query->Filters[]=$filterGroup;
            }

            //Add a join to the column we are going to sum
            if($currentFormula->FieldToUse!='')
            {

                /** @var FieldSettingsBase $fieldSetting */
                $fieldSetting=ArrayUtils::Find($this->FieldSettings,function ($item)use($currentFormula){
                    return $item->Id==$currentFormula->FieldToUse;
                });

                $dependency = new Dependency($this->Loader->RECORDS_DETAIL_TABLE, 'aggregation');
                $dependency->Comparisons[] = new ColumnComparison($formulaRootTable, 'entry_id', 'Equal', 'aggregation', 'entry_id');
                $dependency->Comparisons[] = new FixedValueComparison('aggregation', 'field_id', 'Equal', $currentFormula->FieldToUse);

                if($fieldSetting!=null&&$fieldSetting->GetAggregationPath()!='')
                {
                    $dependency->Comparisons[]=new FixedValueComparison('aggregation','path_id','Equal',$fieldSetting->GetAggregationPath());

                }
                $query->AddDependency($dependency);
            }

            if($isGlobal)
                $this->GlobalFormulas[$currentFormula->Id]=$query->Execute();
            else
                $formulas.=',('.$query->Execute().') ' .'formula_'.$currentFormula->Id;



        }
        return $formulas;

    }

    private function AddFormulaDependencies()
    {
        foreach($this->Formulas as $currentFormula)
        {
            foreach($currentFormula->Condition->ConditionGroups as $conditionGroup)
                foreach($conditionGroup->ConditionLines as $conditionLine)
                {
                    $tableId=$this->RootID.'_'.$conditionLine->FieldId.'_'.$conditionLine->PathId;
                    $dependency=new Dependency($this->Loader->RECORDS_DETAIL_TABLE,$tableId);
                    $dependency->Comparisons[]=new ColumnComparison($this->RootID,'entry_id','Equal',$tableId,'entry_id');
                    $dependency->Comparisons[]=new FixedValueComparison($tableId,'field_id','Equal',$conditionLine->FieldId);

                    if($conditionLine->PathId=='Value'||$conditionLine->PathId=='')
                        $dependency->Comparisons[]=new FixedValueComparison($tableId,'path_id','IsNull',null);
                    else
                        $dependency->Comparisons[]=new FixedValueComparison($tableId,'path_id','Equal',$conditionLine->PathId);

                    $this->AddDependency($dependency);
                }


        }

    }


}