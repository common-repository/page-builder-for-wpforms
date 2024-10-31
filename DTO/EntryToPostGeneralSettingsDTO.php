<?php 

namespace rnpagebuilder\DTO;

class EntryToPostGeneralSettingsDTO extends GeneralSettingsOptionsDTO{
	/** @var Numeric */
	public $PageId;
	/** @var Boolean */
	public $Enable;
	/** @var Numeric */
	public $FeaturedImageField;
	/** @var string */
	public $Category;
	/** @var String[] */
	public $Tags;
	/** @var string */
	public $PostStatus;
	/** @var string */
	public $PostType;
	public $Title;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type='EntryPost';
		$this->PageId=0;
		$this->Enable=false;
		$this->FeaturedImageField=0;
		$this->Category='';
		$this->PostStatus='Published';
		$this->PostType='page';
		$this->Tags=[];
		$this->Title=null;
		$this->AddType("Tags","String");
		$this->AddType("Title","Object");
	}
}

