<?php 

namespace rnpagebuilder\DTO;

class InnerSectionBlockOptionsDTO extends BlockBaseOptionsDTO{
	/** @var RowOptionsDTO[] */
	public $Rows;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=BlockTypeEnumDTO::$InnerSection;
		$this->Rows=[];
		$this->AddType("Rows","RowOptionsDTO");
	}
}

