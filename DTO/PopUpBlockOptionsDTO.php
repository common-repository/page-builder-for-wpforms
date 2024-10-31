<?php 

namespace rnpagebuilder\DTO;

class PopUpBlockOptionsDTO extends BlockBaseOptionsDTO{
	/** @var IconOptionsDTO */
	public $Icon;
	/** @var RowOptionsDTO[] */
	public $Rows;
	/** @var string */
	public $Text;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=BlockTypeEnumDTO::$PopUp;
		$this->Rows=[];
		$this->Icon=(new IconOptionsDTO())->Merge();
		$this->Text='Open Popup';
		$this->AddType("Rows","RowOptionsDTO");
	}
}

