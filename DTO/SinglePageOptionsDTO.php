<?php 

namespace rnpagebuilder\DTO;

class SinglePageOptionsDTO extends PageBuilderBaseOptionsDTO{
	/** @var SinglePageGeneralSettingsDTO */
	public $GeneralSettings;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type='Single';
		$this->GeneralSettings=(new SinglePageGeneralSettingsDTO())->Merge();
	}
}

