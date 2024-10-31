<?php 

namespace rnpagebuilder\DTO;

class CalendarPageOptionsDTO extends PageBuilderBaseOptionsDTO{
	/** @var CalendarGeneralSettingsOptionsDTO */
	public $GeneralSettings;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->GeneralSettings=(new CalendarGeneralSettingsOptionsDTO())->Merge();
		$this->Type='Calendar';
	}
}

