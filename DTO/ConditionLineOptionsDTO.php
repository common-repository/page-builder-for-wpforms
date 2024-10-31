<?php 

namespace rnpagebuilder\DTO;

use rnpagebuilder\DTO\core\StoreBase;

class ConditionLineOptionsDTO extends StoreBase{
	/** @var string */
	public $FieldId;
	/** @var string */
	public $PathId;
	/** @var Boolean */
	public $HasMapping;
	public $Comparison;
	public $Value;
	public $Type;
	public $SubType;
	/** @var string */
	public $Column;
	public $AdditionalOptions;
	/** @var Numeric */
	public $Id;


	public function LoadDefaultValues(){
		$this->Id=0;
		$this->HasMapping=false;
		$this->FieldId='';
		$this->Comparison=ComparisonTypeEnumDTO::$None;
		$this->Value='';
		$this->Type=ConditionLineTypeEnumDTO::$None;
		$this->SubType=FieldTypeEnumDTO::$None;
		$this->PathId='';
		$this->Column='';
		$this->AdditionalOptions=null;
		$this->AddType("Value","Object");
		$this->AddType("AdditionalOptions","Object");
	}
	public function GetValueFromLoader($property,$value){
		switch($property){
			case "Value":
				return \rnpagebuilder\DTO\core\Factories\ConditionLineValueFactory::GetValue($this,$value);
			default:
				return parent::GetValueFromLoader($property, $value);
		}
	}
}

