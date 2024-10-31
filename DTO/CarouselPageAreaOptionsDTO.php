<?php 

namespace rnpagebuilder\DTO;

class CarouselPageAreaOptionsDTO extends PageAreaBaseOptionsDTO{
	/** @var Numeric */
	public $MaximumNumberOfRecordsPerPage;
	/** @var string */
	public $ItemWidth;
	/** @var string */
	public $ItemHeight;
	/** @var Boolean */
	public $AddSliderPagination;
	/** @var Boolean */
	public $AutoPlay;
	/** @var Numeric */
	public $AutoPlayDelay;
	public $EmptyMessage;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->EmptyMessage=null;
		$this->MaximumNumberOfRecordsPerPage=20;
		$this->ItemWidth='';
		$this->ItemHeight='';
		$this->AddSliderPagination=false;
		$this->AutoPlay=false;
		$this->AutoPlayDelay=2000;
		$this->AddType("EmptyMessage","Object");
	}
}

