<?php 

namespace rnpagebuilder\DTO;

class MultiplePageAreaOptionsDTO extends PageAreaBaseOptionsDTO{
	/** @var Numeric */
	public $MaximumNumberOfRecordsPerPage;
	/** @var string */
	public $ItemWidth;
	public $EmptyMessage;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->EmptyMessage=null;
		$this->MaximumNumberOfRecordsPerPage=20;
		$this->ItemWidth='';
		$this->AddType("EmptyMessage","Object");
	}
}

