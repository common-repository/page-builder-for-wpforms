<?php 

namespace rnpagebuilder\DTO;

class RunnableSearchUserOptionsDTO extends RunnableSearchFieldBaseOptionsDTO{
	/** @var string */
	public $UserSearchType;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->UserSearchType="";
	}
}

