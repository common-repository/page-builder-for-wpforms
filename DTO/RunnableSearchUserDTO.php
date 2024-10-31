<?php 

namespace rnpagebuilder\DTO;

class RunnableSearchUserDTO extends RunnableSearchFieldBaseOptionsDTO{
	/** @var string */
	public $SearchType;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->SearchType="";
	}
}

