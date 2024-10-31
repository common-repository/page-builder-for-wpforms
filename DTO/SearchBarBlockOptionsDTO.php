<?php 

namespace rnpagebuilder\DTO;

class SearchBarBlockOptionsDTO extends BlockBaseOptionsDTO{
	/** @var string */
	public $Label;
	/** @var string */
	public $SearchButtonLabel;
	/** @var SearchFieldBaseOptionsDTO[] */
	public $Fields;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=BlockTypeEnumDTO::$SearchBar;
		$this->Label='Search Criteria';
		$this->SearchButtonLabel='Search';
		$this->Fields=[];
		$this->AddType("Fields","SearchFieldBaseOptionsDTO");
	}
}

