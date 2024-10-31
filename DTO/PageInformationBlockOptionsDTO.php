<?php 

namespace rnpagebuilder\DTO;

class PageInformationBlockOptionsDTO extends BlockBaseOptionsDTO{
	public $Text;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=BlockTypeEnumDTO::$PageInformation;
		$this->Text=null;
		$this->AddType("Text","Object");
	}
}

