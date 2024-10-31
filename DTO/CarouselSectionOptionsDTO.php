<?php 

namespace rnpagebuilder\DTO;

class CarouselSectionOptionsDTO extends PageSectionBaseOptionsDTO{
	/** @var GridColumnBaseOptionsDTO[] */
	public $Columns;
	/** @var string */
	public $GridStyle;
	/** @var Boolean */
	public $Stripped;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Columns=[];
		$this->Type='Carousel';
		$this->GridStyle='';
		$this->Stripped=false;
		$this->AddType("Columns","GridColumnBaseOptionsDTO");
	}
	public function GetValueFromLoader($property,$value){
		switch($property){
			case "Columns":
				return \rnpagebuilder\DTO\core\Factories\CellTemplateFactory::GetOptionsFromArray($value);
		}
	}
}

