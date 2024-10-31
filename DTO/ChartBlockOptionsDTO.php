<?php 

namespace rnpagebuilder\DTO;

class ChartBlockOptionsDTO extends BlockBaseOptionsDTO{
	public $ChartType;
	/** @var FilterConditionOptionsDTO */
	public $Condition;
	/** @var string */
	public $FieldId;
	/** @var string */
	public $PathId;
	/** @var string */
	public $OperationType;
	/** @var string */
	public $OperationField;
	/** @var Boolean */
	public $RemoveLegend;
	/** @var string */
	public $Title;
	public $SubValue;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->ChartType=ChartTypeEnumDTO::$Bar;
		$this->Type=BlockTypeEnumDTO::$Chart;
		$this->Condition=(new FilterConditionOptionsDTO())->Merge();
		$this->FieldId='';
		$this->PathId='';
		$this->OperationType=OperationTypeEnumDTO::$Count;
		$this->OperationField='';
		$this->RemoveLegend=false;
		$this->Title='';
		$this->SubValue=null;
		$this->AddType("SubValue","Object");
	}
}

