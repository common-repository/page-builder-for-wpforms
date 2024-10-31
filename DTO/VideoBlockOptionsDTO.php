<?php 

namespace rnpagebuilder\DTO;

class VideoBlockOptionsDTO extends BlockBaseOptionsDTO{
	public $MediaData;
	/** @var Boolean */
	public $ShowControls;
	/** @var Boolean */
	public $Loop;
	/** @var Boolean */
	public $AutoPlay;
	/** @var Boolean */
	public $Muted;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=BlockTypeEnumDTO::$Video;
		$this->MediaData=null;
		$this->ShowControls=false;
		$this->AutoPlay=true;
		$this->Loop=false;
		$this->Muted=true;
		$this->AddType("MediaData","Object");
	}
}

