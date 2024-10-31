<?php 

namespace rnpagebuilder\DTO;

class SinglePageGeneralSettingsDTO extends GeneralSettingsOptionsDTO{
	/** @var Numeric */
	public $PageId;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type='Single';
		$this->PageId=0;
	}
}

