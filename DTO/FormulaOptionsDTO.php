<?php 

namespace rnpagebuilder\DTO;

use rnpagebuilder\DTO\core\StoreBase;

class FormulaOptionsDTO extends StoreBase{
	/** @var Numeric */
	public $Id;
	/** @var string */
	public $FormulaType;
	/** @var string */
	public $FormToUse;
	/** @var string */
	public $FieldToUse;
	/** @var ConditionOptionsBaseDTO */
	public $Condition;


	public function LoadDefaultValues(){
		$this->Id=0;
		$this->FormulaType='';
		$this->FormToUse='';
		$this->FieldToUse='';
		$this->Condition=(new ConditionOptionsBaseDTO())->Merge();
	}
}

