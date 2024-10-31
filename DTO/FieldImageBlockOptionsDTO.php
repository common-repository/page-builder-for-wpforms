<?php 

namespace rnpagebuilder\DTO;

class FieldImageBlockOptionsDTO extends BlockBaseOptionsDTO{
	/** @var string */
	public $FieldId;
	/** @var Boolean */
	public $IsClickable;
	/** @var ClickActionOptionsDTO */
	public $ClickAction;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=BlockTypeEnumDTO::$FieldImage;
		$this->FieldId='';
		$this->IsClickable=false;
		$this->ClickAction=(new ClickActionOptionsDTO())->Merge();
	}
}

