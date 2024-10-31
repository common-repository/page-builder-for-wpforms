<?php 

namespace rnpagebuilder\DTO;

class QRCodeBlockOptionsDTO extends BlockBaseOptionsDTO{
	public $Content;
	public $Value;
	/** @var IconOptionsDTO */
	public $Icon;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=BlockTypeEnumDTO::$QRCode;
		$this->Content=null;
		$this->AddType("Content","Object");
		$this->AddType("Value","Object");
	}
}

