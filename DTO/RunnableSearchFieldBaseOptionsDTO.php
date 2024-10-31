<?php 

namespace rnpagebuilder\DTO;

use rnpagebuilder\DTO\core\StoreBase;

class RunnableSearchFieldBaseOptionsDTO extends StoreBase{
	/** @var Numeric */
	public $Id;
	/** @var string */
	public $SearchType;


	public function LoadDefaultValues(){
		$this->Id=0;
		$this->SearchType='';
	}
}

