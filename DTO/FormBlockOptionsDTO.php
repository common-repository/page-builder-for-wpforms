<?php 

namespace rnpagebuilder\DTO;

class FormBlockOptionsDTO extends RNBlockBaseOptionsDTO{
	public $Src;
	/** @var string */
	public $Label;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Src=null;
		$this->Type=BlockTypeEnumDTO::$Form;
		$this->Label="Form";
		$this->AddType("Src","Object");
	}
}

