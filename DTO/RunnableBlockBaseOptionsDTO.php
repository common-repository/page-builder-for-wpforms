<?php 

namespace rnpagebuilder\DTO;

use rnpagebuilder\DTO\core\StoreBase;

class RunnableBlockBaseOptionsDTO extends StoreBase{
	/** @var Numeric */
	public $Id;
	/** @var string */
	public $Type;


	public function LoadDefaultValues(){
		$this->Id=0;
		$this->Type='';
	}
}

