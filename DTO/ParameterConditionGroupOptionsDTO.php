<?php 

namespace rnpagebuilder\DTO;

class ParameterConditionGroupOptionsDTO extends ConditionGroupOptionsDTO{
	/** @var ParameterConditionLineOptionsDTO[] */
	public $ConditionLines;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->AddType("ConditionLines","ParameterConditionLineOptionsDTO");
	}
}

