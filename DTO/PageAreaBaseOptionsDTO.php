<?php 

namespace rnpagebuilder\DTO;

use rnpagebuilder\DTO\core\StoreBase;

class PageAreaBaseOptionsDTO extends StoreBase{
	/** @var PageSectionBaseOptionsDTO[] */
	public $Sections;
	/** @var string */
	public $Id;
	/** @var string */
	public $Style;
	/** @var string */
	public $StyleMobile;


	public function LoadDefaultValues(){
		$this->Sections=[];
		$this->Id='';
		$this->Style='';
		$this->StyleMobile='';
		$this->AddType("Sections","PageSectionBaseOptionsDTO");
	}
	public function GetValueFromLoader($property,$value){
		switch($property){
			case "Sections":
				return \rnpagebuilder\DTO\core\Factories\SectionFactory::GetSectionFromArray($value);
		}
	}
}

