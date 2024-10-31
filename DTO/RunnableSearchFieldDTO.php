<?php 

namespace rnpagebuilder\DTO;

use rnpagebuilder\DTO\core\StoreBase;

class RunnableSearchFieldDTO extends StoreBase{
	/** @var Numeric */
	public $Id;


	public function LoadDefaultValues(){
		$this->Id=0;
	}
}

