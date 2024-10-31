<?php 

namespace rnpagebuilder\DTO;

class SummaryBlockOptionsDTO extends BlockBaseOptionsDTO{


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=BlockTypeEnumDTO::$FieldSummary;
	}
}

