<?php 

namespace rnpagebuilder\DTO;

use rnpagebuilder\DTO\core\StoreBase;

class RNBlockBaseOptionsDTO extends StoreBase{
	public $Type;
	/** @var Numeric */
	public $Id;
	/** @var Numeric */
	public $Width;


	public function LoadDefaultValues(){
		$this->Type=BlockTypeEnumDTO::$List;
		$this->Id=0;
		$this->Width=100;
	}
}

