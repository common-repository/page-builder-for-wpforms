<?php 

namespace rnpagebuilder\DTO;

class ParameterConditionOptionsDTO extends ConditionOptionsBaseDTO{
	/** @var ParameterConditionGroupOptionsDTO[] */
	public $ConditionGroups;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->AddType("ConditionGroups","ParameterConditionGroupOptionsDTO");
	}
}

