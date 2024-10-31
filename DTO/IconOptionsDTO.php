<?php 

namespace rnpagebuilder\DTO;

use rnpagebuilder\DTO\core\StoreBase;

class IconOptionsDTO extends StoreBase{
	/** @var string */
	public $Type;
	/** @var string */
	public $Source;
	/** @var string */
	public $Value;


	public function LoadDefaultValues(){
		$this->Type='Icon';
		$this->Source='';
		$this->Value='';
	}
}

