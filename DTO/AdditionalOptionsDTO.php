<?php 

namespace rnpagebuilder\DTO;

use rnpagebuilder\DTO\core\StoreBase;

class AdditionalOptionsDTO extends StoreBase{
	/** @var string */
	public $Nonce;
	public $FormList;
	public $DataSourcesData;
	public $PostItems;


	public function LoadDefaultValues(){
		$this->Nonce="";
		$this->FormList=null;
		$this->DataSourcesData=[];
		$this->PostItems=[];
		$this->AddType("FormList","Object");
		$this->AddType("DataSourcesData","Object");
		$this->AddType("PostItems","Object");
	}
}

