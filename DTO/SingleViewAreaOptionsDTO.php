<?php 

namespace rnpagebuilder\DTO;

class SingleViewAreaOptionsDTO extends PageAreaBaseOptionsDTO{
	public $EmptyMessage;
	/** @var string */
	public $EntryVisibility;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->EmptyMessage=null;
		$this->EntryVisibility='private';
		$this->AddType("EmptyMessage","Object");
	}
}

