<?php 

namespace rnpagebuilder\DTO;

class FieldBlockOptionsDTO extends BlockBaseOptionsDTO{
	/** @var string */
	public $Label;
	/** @var string */
	public $FieldId;
	/** @var string */
	public $PathId;
	/** @var Boolean */
	public $Editable;
	/** @var String[] */
	public $AllowedRoles;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=BlockTypeEnumDTO::$Field;
		$this->FieldId='';
		$this->PathId='';
		$this->Label='';
		$this->Editable=false;
		$this->AllowedRoles=[];
		$this->AddType("AllowedRoles","String");
	}
}

