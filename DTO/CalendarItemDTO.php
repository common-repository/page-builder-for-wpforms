<?php 

namespace rnpagebuilder\DTO;

use rnpagebuilder\DTO\core\StoreBase;

class CalendarItemDTO extends StoreBase{
	/** @var string */
	public $Label;
	/** @var string */
	public $Tooltip;
	/** @var string */
	public $StartDate;
	/** @var string */
	public $EndDate;


	public function LoadDefaultValues(){
		$this->Label="";
		$this->Tooltip="";
		$this->StartDate="";
		$this->EndDate="";
	}
}

