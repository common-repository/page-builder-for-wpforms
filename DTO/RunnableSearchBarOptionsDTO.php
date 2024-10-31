<?php 

namespace rnpagebuilder\DTO;

class RunnableSearchBarOptionsDTO extends RunnableBlockBaseOptionsDTO{
	public $Fields;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Fields=[];
		$this->AddType("Fields","Object");
	}
}

