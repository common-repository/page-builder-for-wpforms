<?php 

namespace rnpagebuilder\DTO;

class RunnableNavigatorOptionsDTO extends RunnableBlockBaseOptionsDTO{
	/** @var Numeric */
	public $NextIndex;
	/** @var Numeric */
	public $PreviousIndex;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->NextIndex=0;
		$this->PreviousIndex=0;
	}
}

