<?php 

namespace rnpagebuilder\DTO;

use rnpagebuilder\DTO\core\StoreBase;

class RNPageOptionsDTO extends StoreBase{
	/** @var DataSourceBaseOptionsDTO[] */
	public $DataSources;
	/** @var RNRowOptionsDTO[] */
	public $Rows;
	/** @var string */
	public $Style;


	public function LoadDefaultValues(){
		$this->DataSources=[];
		$this->Rows=[];
		$this->Style="";
		$this->AddType("Rows","RNRowOptionsDTO");
	}
	public function GetValueFromLoader($property,$value){
		switch($property){
			case "DataSources":
				return \rnpagebuilder\DTO\core\Factories\DataSourceOptionsFactory::GetOptions($value);
			default:
				return parent::GetValueFromLoader($property, $value);
		}
	}
}

