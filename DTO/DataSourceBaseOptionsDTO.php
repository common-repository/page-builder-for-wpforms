<?php 

namespace rnpagebuilder\DTO;

use rnpagebuilder\DTO\core\StoreBase;

class DataSourceBaseOptionsDTO extends StoreBase{
	/** @var ColumnBaseOptionsDTO[] */
	public $Columns;
	public $Type;
	/** @var Numeric */
	public $InitialNeededRows;
	/** @var Boolean */
	public $NeedsRowCount;
	/** @var Numeric */
	public $Id;
	/** @var String */
	public $FieldsUsed;


	public function LoadDefaultValues(){
		$this->Columns=[];
		$this->InitialNeededRows=0;
		$this->NeedsRowCount=false;
		$this->Id=0;
		$this->FieldsUsed=[];
		$this->AddType("Columns","ColumnBaseOptionsDTO");
		$this->AddType("FieldsUsed","String");
	}
}

