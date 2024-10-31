<?php 

namespace rnpagebuilder\DTO;

class CalendarGeneralSettingsOptionsDTO extends GeneralSettingsOptionsDTO{
	/** @var string */
	public $StartDateField;
	/** @var string */
	public $EndDateField;
	public $Text;
	/** @var Numeric */
	public $PopUpWidth;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type='Calendar';
		$this->StartDateField='';
		$this->EndDateField='';
		$this->Text=null;
		$this->PopUpWidth=400;
		$this->AddType("Text","Object");
	}
}

