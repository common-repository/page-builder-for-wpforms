<?php 

namespace rnpagebuilder\DTO;

class CustomCellTemplateOptionsDTO extends GridColumnBaseOptionsDTO{
	/** @var RowOptionsDTO[] */
	public $Rows;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type='Custom';
		$this->Rows=[];
		$this->AddType("Rows","RowOptionsDTO");
	}
}

