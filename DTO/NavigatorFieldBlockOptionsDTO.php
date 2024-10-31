<?php 

namespace rnpagebuilder\DTO;

class NavigatorFieldBlockOptionsDTO extends RNBlockBaseOptionsDTO{
	/** @var string */
	public $LabelType;
	public $Text;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->LabelType="RowCount";
		$this->Type=BlockTypeEnumDTO::$Navigator;
		$this->Text=null;
		$this->AddType("Text","Object");
	}
}

