<?php 

namespace rnpagebuilder\DTO;

class RunnableCalendarOptionsDTO extends RunnableBlockBaseOptionsDTO{
	/** @var string */
	public $Mode;
	/** @var CalendarItemDTO[] */
	public $Items;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Mode="";
		$this->Items=[];
		$this->AddType("Items","CalendarItemDTO");
	}
}

