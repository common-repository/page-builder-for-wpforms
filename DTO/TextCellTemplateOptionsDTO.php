<?php 

namespace rnpagebuilder\DTO;

class TextCellTemplateOptionsDTO extends GridColumnBaseOptionsDTO{
	public $Text;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Text='';
		$this->Type='Text';
		$this->AddType("Text","Object");
	}
}

