<?php 

namespace rnpagebuilder\DTO;

class RunnableGridOptionsDTO extends RunnableBlockBaseOptionsDTO{
	/** @var Numeric */
	public $CurrentPageIndex;
	/** @var Numeric */
	public $TotalNumberOfPages;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->CurrentPageIndex=0;
		$this->TotalNumberOfPages=0;
	}
}

