<?php 

namespace rnpagebuilder\DTO;

use rnpagebuilder\DTO\core\StoreBase;

class ClickActionOptionsDTO extends StoreBase{
	public $LinkType;
	public $Value;
	/** @var Boolean */
	public $OpenInNewTab;


	public function LoadDefaultValues(){
		$this->LinkType=LinkTypeEnumDTO::$URL;
		$this->Value=null;
		$this->OpenInNewTab=false;
		$this->AddType("Value","Object");
	}
}

