<?php 

namespace rnpagebuilder\DTO;

use rnpagebuilder\DTO\core\StoreBase;

class PageSectionBaseOptionsDTO extends StoreBase{
	/** @var string */
	public $Id;
	/** @var string */
	public $Type;
	/** @var RowOptionsDTO[] */
	public $Rows;


	public function LoadDefaultValues(){
		$this->Rows=[];
		$this->Id='';
		$this->Type='';
		$this->AddType("Rows","RowOptionsDTO");
	}
}

