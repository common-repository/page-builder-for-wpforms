<?php 

namespace rnpagebuilder\DTO;

use rnpagebuilder\DTO\core\StoreBase;

class GeneralSettingsOptionsDTO extends StoreBase{
	/** @var string */
	public $MaxWidth;
	/** @var string */
	public $Type;


	public function LoadDefaultValues(){
		$this->MaxWidth='900';
		$this->Type='Basic';
	}
}

