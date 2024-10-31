<?php 

namespace rnpagebuilder\DTO;

class LinkBlockOptionsDTO extends BlockBaseOptionsDTO{
	public $Text;
	/** @var string */
	public $Style;
	public $Value;
	/** @var Boolean */
	public $OpenInNewTab;
	public $LinkType;
	/** @var IconOptionsDTO */
	public $Icon;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=BlockTypeEnumDTO::$Link;
		$this->Text=null;
		$this->Style='Link';
		$this->OpenInNewTab=false;
		$this->LinkType=LinkTypeEnumDTO::$URL;
		$this->Value=null;
		$this->Icon=(new IconOptionsDTO())->Merge();
		$this->AddType("Text","Object");
		$this->AddType("Value","Object");
	}
}

