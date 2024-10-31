<?php 

namespace rnpagebuilder\DTO;

use rnpagebuilder\DTO\core\StoreBase;

class ColumnBaseOptionsDTO extends StoreBase{
	/** @var string */
	public $Id;
	/** @var string */
	public $Label;


	public function LoadDefaultValues(){
		$this->Id="";
		$this->Label="";
	}
}

