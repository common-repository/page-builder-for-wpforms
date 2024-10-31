<?php 

namespace rnpagebuilder\DTO;

use rnpagebuilder\DTO\core\StoreBase;

class BlockBaseOptionsDTO extends StoreBase{
	/** @var Numeric */
	public $Id;
	public $Type;
	/** @var string */
	public $Class;
	/** @var string */
	public $Styles;


	public function LoadDefaultValues(){
		$this->Type=BlockTypeEnumDTO::$None;
		$this->Id=0;
		$this->Class='';
		$this->Styles='';
	}
}

