<?php 

namespace rnpagebuilder\DTO;

class NavigationBlockOptionsDTO extends BlockBaseOptionsDTO{
	/** @var string */
	public $Label;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=BlockTypeEnumDTO::$Navigation;
		$this->Label='Navigation';
	}
}

