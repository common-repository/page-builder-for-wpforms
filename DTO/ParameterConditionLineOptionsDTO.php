<?php 

namespace rnpagebuilder\DTO;

class ParameterConditionLineOptionsDTO extends ConditionLineOptionsDTO{
	/** @var Boolean */
	public $IsRequired;
	/** @var string */
	public $ParameterName;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->IsRequired=false;
		$this->ParameterName='';
	}
}

