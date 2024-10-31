<?php 

namespace rnpagebuilder\DTO;

class RunnableSearchDateOptionsDTO extends RunnableSearchFieldBaseOptionsDTO{
	/** @var string */
	public $Format;
	/** @var string */
	public $Style;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Style="Date";
		$this->Format="m-d-Y";
	}
}

