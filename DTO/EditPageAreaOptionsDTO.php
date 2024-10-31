<?php 

namespace rnpagebuilder\DTO;

class EditPageAreaOptionsDTO extends PageAreaBaseOptionsDTO{
	/** @var String[] */
	public $AllowedRoles;
	public $NotAllowedMessage;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->AllowedRoles=[];
		$this->NotAllowedMessage=null;
		$this->AddType("AllowedRoles","String");
		$this->AddType("NotAllowedMessage","Object");
	}
}

