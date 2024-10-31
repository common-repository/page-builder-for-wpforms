<?php


namespace rnpagebuilder\PageBuilder\DataSources\FormDataSource;


use rnpagebuilder\core\db\core\RepositoryBase;
use rnpagebuilder\core\db\FormRepository;
use rnpagebuilder\core\Exception\ExceptionSeverity;
use rnpagebuilder\core\Exception\FriendlyException;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\FieldSettingsBase;
use rnpagebuilder\core\Utils\ArrayUtils;
use rnpagebuilder\DTO\ConditionGroupOptionsDTO;
use rnpagebuilder\DTO\ConditionLineOptionsDTO;
use rnpagebuilder\DTO\FormDataSourceOptionsDTO;
use rnpagebuilder\DTO\SortItemOptionsDTO;
use rnpagebuilder\PageBuilder\DataSources\Core\DataSourceBase;
use rnpagebuilder\PageBuilder\DataSources\Core\DataSourceInfo;
use rnpagebuilder\PageBuilder\QueryBuilder\Comparison\ColumnComparison;
use rnpagebuilder\PageBuilder\QueryBuilder\Comparison\ComparisonFormatter\NumericComparisonFormatter;
use rnpagebuilder\PageBuilder\QueryBuilder\Comparison\DateFixedValueComparison;
use rnpagebuilder\PageBuilder\QueryBuilder\Comparison\EntryIdValueComparison;
use rnpagebuilder\PageBuilder\QueryBuilder\Comparison\FixedValueComparison;
use rnpagebuilder\PageBuilder\QueryBuilder\Comparison\ListFixedValueComparison;
use rnpagebuilder\PageBuilder\QueryBuilder\Comparison\UserValueComparison;
use rnpagebuilder\PageBuilder\QueryBuilder\Filters\FilterGroup;
use rnpagebuilder\PageBuilder\QueryBuilder\Filters\FilterLineBase;
use rnpagebuilder\PageBuilder\QueryBuilder\Filters\MultipleGroupFilter;
use rnpagebuilder\PageBuilder\QueryBuilder\QueryBuilder;
use rnpagebuilder\PageBuilder\QueryBuilder\QueryElement\Dependency;
use rnpagebuilder\PageBuilder\QueryBuilder\QueryElement\QueryElement;

class FormDataSource extends DataSourceBase
{
    /** @var FormDataSourceOptionsDTO */
    public $Options;
    /** @var FormColumn[] */
    public $Columns;
    public $LastQuery;
    private $FormConfig=null;
    /** @var SortItemOptionsDTO[] */
    protected $SortItems;

    /** @var FormRow */
    protected $CurrentRow;
    /** @var FieldSettingsBase[] */
    public $Fields=[];


    /** @var ConditionGroupOptionsDTO[] */
    public $AndAdditionalFilters;

    public function __construct($pageBuilder, $dataSource)
    {
        parent::__construct($pageBuilder, $dataSource);
        $this->AndAdditionalFilters=[];
        $this->SortItems=[];

    }

    public function GetSortItemDirection($fieldId,$pathId)
    {
        foreach($this->SortItems as $currentSortItem)
        {
            if($currentSortItem->FieldId==$fieldId&&$currentSortItem->PathId==$pathId)
            {
                return $currentSortItem->Orientation;
            }
        }

        return null;
    }
    public function AddSortItem($fieldId,$pathId,$orientation)
    {
        $sort=ArrayUtils::Find($this->SortItems,function ($item)use($fieldId,$pathId,$orientation){
            if($item->FieldId==$fieldId&&$item->PathId==$pathId&&$item->Orientation==$orientation)
                return true;
            return false;
        });

        if($sort==null)
        {
            $sort=(new SortItemOptionsDTO())->Merge();
            $sort->FieldId=$fieldId;
            $sort->PathId=$pathId;
            $sort->Orientation=$orientation;
        }

        $this->SortItems[]=$sort;

    }


    public function InitializeColumns()
    {
        foreach($this->Options->Columns as $currentColumn)
        {
            $this->Columns[]=new FormColumn($this,$currentColumn);
        }
        $this->GetFormConfig();
    }

    public function CreateRows($limit=0, $skip=0)
    {
        $queryBuilder=new QueryBuilder($this->PageBuilder->Loader, $this->PageBuilder->Loader->RECORDS_TABLE,'ROOT');
        $column=new QueryElement();

        if(ArrayUtils::Some($this->Options->FieldsUsed,function ($item){return $item=='__userId';}))
        {
            global $wpdb;
            $userDependency=new Dependency($wpdb->users,'USER');
            $userDependency->AddComparison(new ColumnComparison('ROOT','user_id','Equal','USER','id'));
            $queryBuilder->AddDependency($userDependency);

            $column=new QueryElement();
            $column->AddColumn2('USER','user_email','UserEmail');
            $column->AddColumn2('USER','display_name','UserDisplayName');
            $column->AddColumn2('USER','id','UserId');


        }
        $column->AddColumn2('ROOT','reference',$this->Options->Id.'_Reference');
        $column->AddColumn2('ROOT','id',$this->Options->Id.'_EntryId');
        $column->AddColumn2('ROOT','date',$this->Options->Id.'_Date');

        $queryBuilder->AddQueryElement($column);

        $column=new QueryElement();
        $column->AddColumn2('ROOT','entry',$this->Options->Id.'_Entry');
        $queryBuilder->AddQueryElement($column);


        $formIdFilter=new FilterGroup($queryBuilder,'and');
        $filterLine=new FilterLineBase($formIdFilter);
        $filterLine->Filter=new FixedValueComparison('ROOT','form_id','Equal',$this->Options->FormId);
        $formIdFilter->AddFilterLine($filterLine);

        $queryBuilder->Filters[]=$formIdFilter;

        foreach($this->Options->Condition->ConditionGroups as $conditionGroup)
        {
            $filterGroup=new FilterGroup($queryBuilder);
            $queryBuilder->Filters[]=$filterGroup;
            foreach ($conditionGroup->ConditionLines as $conditionLine)
            {
                $this->GenerateQueryElements($queryBuilder,$filterGroup,$conditionLine);
            }

        }

        $multiple=new MultipleGroupFilter('and');
        foreach($this->Options->ParameterCondition->ConditionGroups as $conditionGroup)
        {
            $filterGroup=new FilterGroup($queryBuilder);

            foreach ($conditionGroup->ConditionLines as $conditionLine)
            {
                $value=$this->PageBuilder->GetGetItem($conditionLine->ParameterName);
                if($value==null)
                {
                    if($conditionLine->IsRequired&&$conditionLine->Value=='')
                    {
                        if($this->PageBuilder->IsPreview())
                            continue;
                        else
                            throw new FriendlyException('A required parameter was not filled');
                    }else{
                        if($conditionLine->Value=='')
                            continue;
                    }


                }else{
                    if($conditionLine->SubType=='Multiple')
                    {
                        $value=explode(',',$value);
                    }
                    $conditionLine->Value=$value;
                }
                $this->GenerateQueryElements($queryBuilder,$filterGroup,$conditionLine);
            }
            if(count($filterGroup->FilterLines)>0)
                $multiple->AddGroup($filterGroup);

        }

        if(count($multiple->Groups)>0)
            $queryBuilder->Filters[]=$multiple;

        foreach($this->AndAdditionalFilters as $conditionGroup)
        {
            $filterGroup=new FilterGroup($queryBuilder,'and');
            $queryBuilder->Filters[]=$filterGroup;
            foreach ($conditionGroup->ConditionLines as $conditionLine)
            {
                $this->GenerateQueryElements($queryBuilder,$filterGroup,$conditionLine);
            }

        }


        $count=-1;
        if($this->Options->NeedsRowCount)
        {
            $count=$queryBuilder->GetCount();
        }

        $queryBuilder->SortItems=$this->SortItems;
        $rows= $queryBuilder->GetRows($limit,$skip);

        $rows=$this->SerializeRows($rows);
        $this->Rows=$rows;
        $this->Count=$count;

        if(count($this->Rows)>0)
            $this->CurrentRow=$this->Rows[0];

        $this->LastQuery=$queryBuilder->LastQuery;


    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param FilterGroup $filterGroup
     * @param ConditionLineOptionsDTO $conditionLine
     */
    private function GenerateQueryElements($queryBuilder, $filterGroup,$conditionLine)
    {
        $tableId=null;
        $filterLine=null;
        $column=null;
        switch ($conditionLine->FieldId)
        {
            case '__userId':$filterLine=new FilterLineBase($filterGroup);
                $filterGroup->FilterLines[]=$filterLine;
                $tableId='ROOT';
                $column='user_id';
                break;
            case '__entryId':
                $filterLine=new FilterLineBase($filterGroup);
                $filterGroup->FilterLines[]=$filterLine;
                $tableId='ROOT';
                $column='id';
                break;

            case '__date':
                $filterLine=new FilterLineBase($filterGroup);
                $filterGroup->FilterLines[]=$filterLine;
                $tableId='ROOT';
                $column='date';
                break;
            default:
                $tableId=$conditionLine->FieldId.'_'.$conditionLine->PathId;
                $column='value';

                $dependency=new Dependency($this->PageBuilder->Loader->RECORDS_DETAIL_TABLE,$tableId);
                $dependency->Comparisons[]=new ColumnComparison('ROOT','id','Equal',$tableId,'entry_id');
                $dependency->Comparisons[]=new FixedValueComparison($tableId,'field_id','Equal',$conditionLine->FieldId);

                $queryBuilder->AddDependency($dependency);
                $filterLine=new FilterLineBase($filterGroup);
                $filterGroup->FilterLines[]=$filterLine;



                if($conditionLine->PathId=='Value'||$conditionLine->PathId=='')
                    $dependency->Comparisons[]=new FixedValueComparison($tableId,'path_id','IsNull',null);
                else
                    $dependency->Comparisons[]=new FixedValueComparison($tableId,'path_id','Equal',$conditionLine->PathId);

        }



        switch ($conditionLine->SubType)
        {
            case 'Text':
            case 'Composed':
                $filterLine->Filter=new FixedValueComparison($tableId,$column,$conditionLine->Comparison,$conditionLine->Value);
                break;
            case 'Multiple':
                $filterLine->Filter=new ListFixedValueComparison($this->PageBuilder->Loader->RECORDS_DETAIL_TABLE,$conditionLine->FieldId,$tableId,$column,$conditionLine->Comparison,$conditionLine->Value);
                break;
            case 'Number':

            case 'Time':
                if($column=='value')
                    $column='numericvalue';
                $filterLine->Filter=new FixedValueComparison($tableId,$column,$conditionLine->Comparison,$conditionLine->Value,new NumericComparisonFormatter());
                break;
            case 'DateTime':
            case 'Date':
                $filterLine->Filter=new DateFixedValueComparison($tableId,$column,$conditionLine->Comparison,$conditionLine->Value);
                break;
            case 'User':
                $filterLine->Filter=new UserValueComparison($filterLine, $tableId,$column,$conditionLine->Comparison,$conditionLine->Value);
                break;
            case 'EntryId':
                $filterLine->Filter=new EntryIdValueComparison($filterLine, $tableId,$column,$conditionLine->Comparison,$conditionLine->Value);
                break;
            default:
                throw new FriendlyException('Invalid condition type '.$conditionLine->SubType.', please check the data source filters and make sure they are correct',ExceptionSeverity::$FATAL);
        }

    }

    public function GetOriginalId(){
        $formRepository=new FormRepository($this->PageBuilder->Loader);
        return $formRepository->GetOriginalId($this->Options->FormId);
    }

    public function GetFormConfig(){
        $formRepository=new FormRepository($this->PageBuilder->Loader);
        $this->FormConfig=$formRepository->GetFieldConfig($this->Options->FormId);
        if($this->FormConfig==null)
            throw new FriendlyException('The form configuration was not found are you sure it still exists?',ExceptionSeverity::$FATAL,'The form with id '.$this->Options->FormId.' was not found ');

        $this->Fields=[];
        foreach($this->FormConfig as $currentItem)
        {
            $this->Fields[]=$currentItem;
        }
    }

    private function SerializeRows($rows)
    {
        $rowsToReturn=array();
        foreach($rows as $currentRow)
        {

            if(!isset($currentRow->{$this->Options->Id.'_Reference'})||$currentRow->{$this->Options->Id.'_Reference'}==null)
                continue;

            $newRow=new \stdClass();
            $newRow->Reference=$currentRow->{$this->Options->Id.'_Reference'};

            $newRow->f___date=new \stdClass();
            $newRow->f___date=new \stdClass();
            $newRow->f___date->Unix=strtotime($currentRow->{$this->Options->Id.'_Date'});
            $newRow->f___date->Value=$currentRow->{$this->Options->Id.'_Date'};

            $newRow->f___entryId=(object)['Value'=>$currentRow->{$this->Options->Id.'_EntryId'}];

            $entry=json_decode($currentRow->{$this->Options->Id.'_Entry'});
            foreach($this->Options->FieldsUsed as $currentlyUsedField)
            {

                if($currentlyUsedField=='__userId')
                {
                    $newRow->f___userId=new \stdClass();
                    $newRow->f___userId->Id=$currentRow->UserId;
                    $newRow->f___userId->Email=$currentRow->UserEmail;
                    $newRow->f___userId->Value=$currentRow->UserDisplayName;
                }
            }

            foreach ($entry as $key=>$currentEntryColumn)
            {
                if(strpos($currentEntryColumn->_fieldId,'__')===0)
                    continue;
                $newRow->{'f_' . $currentEntryColumn->_fieldId} = $currentEntryColumn;
                unset($currentEntryColumn->_fieldId);
                unset($entry[$key]);
            }

            $rowsToReturn[] =new FormRow($this,$newRow);

        }

        return $rowsToReturn;


    }

    public function GetFieldById($fieldId)
    {
        foreach($this->Fields as $currentField)
        {
            if($currentField->Id==$fieldId)
                return $currentField;
        }

        return null;

    }

    public function GetFieldLabel($feldId)
    {
        $field=$this->GetFieldById($feldId);
        if($field==null)
            return '';

        return $field->Label;
    }

    public function GetCurrentRowSimilarInput($fieldId)
    {
        $field=$this->GetFieldById($fieldId);
        if($field==null)
            return '';

        $value=null;
        if($this->CurrentRow!=null)
            $value=$this->CurrentRow->GetValue($fieldId);

        return $field->ParseSimilarInput($this->PageBuilder->Twig, $value);

    }

    public function ClearSort()
    {
        $this->SortItems=[];
    }
}