<?php 

namespace rnpagebuilder\DTO;

use rnpagebuilder\DTO\core\StoreBase;

class RNColumnOptionsDTO extends StoreBase{
	/** @var RNBlockBaseOptionsDTO */
	public $Block;
	/** @var Numeric */
	public $Id;


	public function LoadDefaultValues(){
		$this->Block=new RNBlockBaseOptionsDTO();;
		$this->Id=0;
	}
	public function GetValueFromLoader($property,$value){
		switch($property){
			case "Block":
				return \rnpagebuilder\DTO\core\Factories\BlockFactory::GetOptions($this,$value);
			default:
				return parent::GetValueFromLoader($property, $value);
		}
	}
}

