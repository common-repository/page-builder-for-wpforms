<?php 

namespace rnpagebuilder\DTO;

class GridFieldBlockOptionsDTO extends RNBlockBaseOptionsDTO{
	/** @var string */
	public $LabelType;
	/** @var GridColumnOptionsDTO[] */
	public $Columns;
	/** @var Boolean */
	public $EnablePagination;
	/** @var Numeric */
	public $PaginationOptions;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=BlockTypeEnumDTO::$Grid;
		$this->Columns=[];
		$this->EnablePagination=false;
		$this->PaginationOptions=[];
		$this->AddType("Columns","GridColumnOptionsDTO");
		$this->AddType("PaginationOptions","Numeric");
	}
}

