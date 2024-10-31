<?php 

namespace rnpagebuilder\DTO;

class AdditionalSectionOptionsDTO extends PageSectionBaseOptionsDTO{


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Id='Additional';
	}
}

