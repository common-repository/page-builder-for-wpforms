<?php 

namespace rnpagebuilder\DTO;

use rnpagebuilder\DTO\core\StoreBase;

class PageBuilderOptionsDTO extends StoreBase{
	public $Page;
	/** @var string */
	public $Name;
	/** @var Numeric */
	public $Id;
	/** @var String[] */
	public $Dependencies;
	public $Text;


	public function LoadDefaultValues(){
		$this->Name='';
		$this->Id=0;
		$this->Dependencies=[];
		$this->Text=null;
		$this->AddType("Dependencies","String");
		$this->AddType("Text","Object");
	}
}

