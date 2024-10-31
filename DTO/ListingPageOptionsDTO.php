<?php 

namespace rnpagebuilder\DTO;

class ListingPageOptionsDTO extends PageBuilderBaseOptionsDTO{


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type='Listing';
	}
}

