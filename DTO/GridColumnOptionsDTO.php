<?php 

namespace rnpagebuilder\DTO;

use rnpagebuilder\DTO\core\StoreBase;

class GridColumnOptionsDTO extends StoreBase{
	/** @var Numeric */
	public $Id;
	/** @var string */
	public $Header;
	public $ContentType;
	public $Content;
	/** @var Boolean */
	public $IsSortable;
	/** @var Boolean */
	public $IsClickable;
	public $ClickAction;
	public $ClickTarget;
	public $ClickParams;


	public function LoadDefaultValues(){
		$this->Id=0;
		$this->Header="";
		$this->ContentType="field";
		$this->Content=null;
		$this->IsSortable=false;
		$this->IsClickable=false;
		$this->ClickAction=ClickActionEnumDTO::$OpenURL;
		$this->ClickTarget=ClickTargetEnumDTO::$Self;
		$this->ClickParams=null;
		$this->AddType("Content","Object");
		$this->AddType("ClickParams","Object");
	}
}

