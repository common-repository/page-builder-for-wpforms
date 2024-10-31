<?php 

namespace rnpagebuilder\DTO;

class FormDataSourceOptionsDTO extends DataSourceBaseOptionsDTO{
	/** @var ColumnBaseOptionsDTO[] */
	public $Columns;
	/** @var Numeric */
	public $FormId;
	/** @var FilterConditionOptionsDTO */
	public $Condition;
	/** @var ParameterConditionOptionsDTO */
	public $ParameterCondition;
	/** @var SortItemOptionsDTO[] */
	public $Sort;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->FormId=0;
		$this->Columns=[];
		$this->Sort=[];
		$this->Type=DataSourceTypeEnumDTO::$Form;
		$this->Condition=(new FilterConditionOptionsDTO())->Merge();
		$this->ParameterCondition=(new ParameterConditionOptionsDTO())->Merge();
		$this->AddType("Columns","ColumnBaseOptionsDTO");
		$this->AddType("Sort","SortItemOptionsDTO");
	}
}

