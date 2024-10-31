<?php 

namespace rnpagebuilder\DTO;

use rnpagebuilder\DTO\core\StoreBase;

class PageBuilderBaseOptionsDTO extends StoreBase{
	/** @var Numeric */
	public $Id;
	/** @var string */
	public $Type;
	/** @var PageAreaBaseOptionsDTO[] */
	public $Areas;
	/** @var string */
	public $FormId;
	/** @var string */
	public $Name;
	/** @var GeneralSettingsOptionsDTO */
	public $GeneralSettings;
	/** @var FilterConditionOptionsDTO */
	public $Filter;
	/** @var FormulaOptionsDTO[] */
	public $Formulas;
	/** @var SortItemOptionsDTO[] */
	public $Sort;


	public function LoadDefaultValues(){
		$this->Formulas=[];
		$this->Id=0;
		$this->Name='';
		$this->FormId='';
		$this->Areas=[];
		$this->Type='';
		$this->Filter=(new FilterConditionOptionsDTO())->Merge();
		$this->GeneralSettings=(new GeneralSettingsOptionsDTO())->Merge();
		$this->Sort=[];
		$this->AddType("Areas","PageAreaBaseOptionsDTO");
		$this->AddType("Formulas","FormulaOptionsDTO");
		$this->AddType("Sort","SortItemOptionsDTO");
	}
	public function GetValueFromLoader($property,$value){
		switch($property){
			case "Areas":
				return \rnpagebuilder\DTO\core\Factories\AreaFactory::GetOptionsFromList($value);
		}
	}
}

