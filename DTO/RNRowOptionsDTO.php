<?php 

namespace rnpagebuilder\DTO;

use rnpagebuilder\DTO\core\StoreBase;

class RNRowOptionsDTO extends StoreBase{
	/** @var RNColumnOptionsDTO[] */
	public $Columns;
	/** @var Numeric */
	public $Id;


	public function LoadDefaultValues(){
		$this->Columns=[];
		$this->Id=0;
		$this->AddType("Columns","RNColumnOptionsDTO");
	}
}

