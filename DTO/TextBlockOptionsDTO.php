<?php 

namespace rnpagebuilder\DTO;

class TextBlockOptionsDTO extends BlockBaseOptionsDTO{
	/** @var string */
	public $Label;
	public $Text;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=BlockTypeEnumDTO::$Text;
		$this->Label='Text';
		$this->Text=null;
		$this->AddType("Text","Object");
	}
}

