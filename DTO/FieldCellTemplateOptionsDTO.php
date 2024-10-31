<?php 

namespace rnpagebuilder\DTO;

class FieldCellTemplateOptionsDTO extends GridColumnBaseOptionsDTO{
	/** @var string */
	public $FieldId;
	/** @var string */
	public $PathId;
	/** @var Boolean */
	public $IsSortable;
	/** @var Boolean */
	public $Editable;
	/** @var String[] */
	public $AllowedRoles;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type='Field';
		$this->FieldId='';
		$this->PathId='';
		$this->IsSortable=false;
		$this->Editable=false;
		$this->AllowedRoles=[];
		$this->AddType("AllowedRoles","String");
	}
}

