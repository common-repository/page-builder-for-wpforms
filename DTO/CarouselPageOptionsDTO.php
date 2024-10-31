<?php 

namespace rnpagebuilder\DTO;

class CarouselPageOptionsDTO extends PageBuilderBaseOptionsDTO{


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type='Carousel';
	}
}

