<?php 

namespace rnpagebuilder\DTO;

use rnpagebuilder\DTO\core\StoreBase;

class GridColumnBaseOptionsDTO extends StoreBase{
	/** @var Numeric */
	public $ColumnId;
	public $Header;
	/** @var string */
	public $Type;
	/** @var string */
	public $Width;


	public function LoadDefaultValues(){
		$this->ColumnId=0;
		$this->Header='Column';
		$this->Type='Text';
		$this->Width='';
		$this->AddType("Header","Object");
	}
}

