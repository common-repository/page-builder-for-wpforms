<?php 

namespace rnpagebuilder\DTO;

class CalendarBlockOptionsDTO extends RNBlockBaseOptionsDTO{
	/** @var string */
	public $Mode;
	/** @var string */
	public $Label;
	public $ItemLabel;
	/** @var string */
	public $StartDateField;
	/** @var string */
	public $EndDateField;
	public $Tooltip;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Label="Calendar";
		$this->Type=BlockTypeEnumDTO::$Calendar;
		$this->StartDateField="__date";
		$this->EndDateField="__date";
		$this->Mode="month";
		$this->ItemLabel=null;
		$this->Tooltip=null;
		$this->AddType("ItemLabel","Object");
		$this->AddType("Tooltip","Object");
	}
}

