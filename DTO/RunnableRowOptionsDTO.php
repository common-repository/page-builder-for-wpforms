<?php 

namespace rnpagebuilder\DTO;

use rnpagebuilder\DTO\core\StoreBase;

class RunnableRowOptionsDTO extends StoreBase{
	/** @var RunnableColumnOptionsDTO[] */
	public $Columns;


	public function LoadDefaultValues(){
		$this->Columns=[];
		$this->AddType("Columns","RunnableColumnOptionsDTO");
	}
}

