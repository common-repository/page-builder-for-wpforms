<?php 

namespace rnpagebuilder\DTO;

use rnpagebuilder\DTO\core\StoreBase;

class ColumnOptionsDTO extends StoreBase{
	/** @var Numeric */
	public $Id;
	/** @var BlockBaseOptionsDTO[] */
	public $Blocks;
	/** @var Numeric */
	public $WidthPercentage;
	/** @var string */
	public $Class;


	public function LoadDefaultValues(){
		$this->Id=0;
		$this->Blocks=[];
		$this->WidthPercentage=100;
		$this->Class='';
		$this->AddType("Blocks","BlockBaseOptionsDTO");
	}
	public function GetValueFromLoader($property,$value){
		switch($property){
			case "Blocks":
				return \rnpagebuilder\DTO\core\Factories\BlockFactory::GetBlockOptions($value);
			default:
				return parent::GetValueFromLoader($property, $value);
		}
	}
}

