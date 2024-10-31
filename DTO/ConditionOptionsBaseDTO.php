<?php 

namespace rnpagebuilder\DTO;

use rnpagebuilder\DTO\core\StoreBase;

class ConditionOptionsBaseDTO extends StoreBase{
	/** @var Numeric */
	public $Id;
	public $Type;
	/** @var ConditionGroupOptionsDTO[] */
	public $ConditionGroups;
	/** @var ElementUsedOptionsDTO[] */
	public $ElementsUsed;


	public function LoadDefaultValues(){
		$this->Id=0;
		$this->Type=ConditionTypeEnumDTO::$Filter;
		$this->ElementsUsed=[];
		$this->ConditionGroups=[];
		$this->AddType("ConditionGroups","ConditionGroupOptionsDTO");
		$this->AddType("ElementsUsed","ElementUsedOptionsDTO");
	}
}

