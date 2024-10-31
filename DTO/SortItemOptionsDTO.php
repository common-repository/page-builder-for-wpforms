<?php 

namespace rnpagebuilder\DTO;

use rnpagebuilder\DTO\core\StoreBase;

class SortItemOptionsDTO extends StoreBase{
	/** @var Numeric */
	public $Id;
	/** @var string */
	public $FieldId;
	/** @var string */
	public $PathId;
	/** @var string */
	public $Orientation;


	public function LoadDefaultValues(){
		$this->Id=0;
		$this->FieldId='';
		$this->Orientation='desc';
		$this->PathId='';
	}
}

