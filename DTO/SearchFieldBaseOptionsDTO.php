<?php 

namespace rnpagebuilder\DTO;

use rnpagebuilder\DTO\core\StoreBase;

class SearchFieldBaseOptionsDTO extends StoreBase{
	/** @var Numeric */
	public $Id;
	/** @var string */
	public $FieldId;
	/** @var string */
	public $Label;
	/** @var string */
	public $PathId;
	/** @var string */
	public $SubType;
	/** @var string */
	public $ComparisonType;


	public function LoadDefaultValues(){
		$this->FieldId='';
		$this->PathId='';
		$this->ComparisonType='';
		$this->Label='';
		$this->Id=0;
		$this->SubType='';
	}
}

