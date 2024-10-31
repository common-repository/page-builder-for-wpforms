<?php 

namespace rnpagebuilder\DTO;

use rnpagebuilder\DTO\core\StoreBase;

class RowOptionsDTO extends StoreBase{
	/** @var Numeric */
	public $Id;
	/** @var ColumnOptionsDTO[] */
	public $Columns;
	/** @var string */
	public $Class;
	/** @var string */
	public $Styles;


	public function LoadDefaultValues(){
		$this->Columns=[];
		$this->Id=0;
		$this->Class='';
		$this->Styles='';
		$this->AddType("Columns","ColumnOptionsDTO");
	}
}

