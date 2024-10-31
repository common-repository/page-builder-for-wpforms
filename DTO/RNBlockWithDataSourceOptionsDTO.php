<?php 

namespace rnpagebuilder\DTO;

class RNBlockWithDataSourceOptionsDTO extends RNBlockBaseOptionsDTO{
	/** @var Numeric */
	public $DataSourceId;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->DataSourceId=0;
	}
}

