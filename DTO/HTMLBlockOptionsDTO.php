<?php 

namespace rnpagebuilder\DTO;

class HTMLBlockOptionsDTO extends BlockBaseOptionsDTO{
	/** @var string */
	public $Label;
	public $Code;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=BlockTypeEnumDTO::$HTML;
		$this->Label='HTML';
		$this->Code='';
		$this->AddType("Code","Object");
	}
}

