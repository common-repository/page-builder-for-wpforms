<?php 

namespace rnpagebuilder\DTO;

class TextWithFieldsBlockOptionsDTO extends TextBlockOptionsDTO{


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=BlockTypeEnumDTO::$TextWithFields;
	}
}

