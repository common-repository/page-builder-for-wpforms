<?php 

namespace rnpagebuilder\DTO;

use rnpagebuilder\DTO\core\StoreBase;

class RunnablePageOptionsDTO extends StoreBase{
	/** @var string */
	public $Id;
	/** @var RunnableRowOptionsDTO[] */
	public $Rows;
	public $AdditionalOptions;


	public function LoadDefaultValues(){
		$this->Id='';
		$this->Rows=[];
		$this->AdditionalOptions=null;
		$this->AddType("Rows","RunnableRowOptionsDTO");
		$this->AddType("AdditionalOptions","Object");
	}
}

