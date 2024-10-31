<?php 

namespace rnpagebuilder\DTO;

class ImageBlockOptionsDTO extends BlockBaseOptionsDTO{
	public $MediaData;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=BlockTypeEnumDTO::$Image;
		$this->MediaData=null;
		$this->AddType("MediaData","Object");
	}
}

