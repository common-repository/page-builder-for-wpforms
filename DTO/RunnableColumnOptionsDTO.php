<?php 

namespace rnpagebuilder\DTO;

use rnpagebuilder\DTO\core\StoreBase;

class RunnableColumnOptionsDTO extends StoreBase{
	/** @var RunnableBlockBaseOptionsDTO */
	public $Block;


	public function LoadDefaultValues(){
		$this->Block=null;
	}
	public function GetValueFromLoader($property,$value){
		switch($property){
			case "Block":
				return \rnpagebuilder\DTO\core\Factories\RunnableBlockFactory::GetOptions($property,$value);
		}
        return null;
	}
}

