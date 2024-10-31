<?php 

namespace rnpagebuilder\DTO;

use rnpagebuilder\DTO\core\StoreBase;

class SearchFieldDTO extends StoreBase{
	/** @var string */
	public $FieldId;


	public function LoadDefaultValues(){
		$this->FieldId="";
	}
}

