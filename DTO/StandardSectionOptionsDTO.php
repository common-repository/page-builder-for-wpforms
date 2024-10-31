<?php 

namespace rnpagebuilder\DTO;

class StandardSectionOptionsDTO extends PageSectionBaseOptionsDTO{
	/** @var string */
	public $Styles;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type='Standard';
		$this->Styles='';
	}
}

