<?php 

namespace rnpagebuilder\DTO;

class LinkCellTemplateOptionsDTO extends GridColumnBaseOptionsDTO{
	public $LinkType;
	/** @var string */
	public $Value;
	public $Text;
	/** @var Boolean */
	public $OpenInNewTab;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type='Link';
		$this->LinkType=LinkTypeEnumDTO::$URL;
		$this->Text=null;
		$this->OpenInNewTab=false;
		$this->Value='';
		$this->AddType("Text","Object");
	}
}

