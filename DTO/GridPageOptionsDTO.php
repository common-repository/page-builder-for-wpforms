<?php 

namespace rnpagebuilder\DTO;

class GridPageOptionsDTO extends PageBuilderBaseOptionsDTO{


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type='Grid';
	}
}

