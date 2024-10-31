<?php 

namespace rnpagebuilder\DTO;

class EntryToPostOptionsDTO extends PageBuilderBaseOptionsDTO{
	/** @var EntryToPostGeneralSettingsDTO */
	public $GeneralSettings;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type='EntryPost';
		$this->GeneralSettings=(new EntryToPostGeneralSettingsDTO())->Merge();
	}
}

