<?php 

namespace rnpagebuilder\DTO;

class ListBlockOptionsDTO extends RNBlockBaseOptionsDTO{
	/** @var RNRowOptionsDTO[] */
	public $Rows;
	/** @var Numeric */
	public $ItemsPerPage;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=BlockTypeEnumDTO::$List;
		$this->Rows=[];
		$this->ItemsPerPage=10;
		$this->AddType("Rows","RNRowOptionsDTO");
	}
}

