<?php 

namespace rnpagebuilder\DTO;

class FormFieldBlockOptionsDTO extends RNBlockWithDataSourceOptionsDTO{
	/** @var Numeric */
	public $DataSourceId;
	public $LabelType;
	/** @var String */
	public $FieldId;
	/** @var string */
	public $Label;
	/** @var Boolean */
	public $HideWhenEmpty;
	/** @var string */
	public $FieldStyle;
	/** @var string */
	public $LabelPosition;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=BlockTypeEnumDTO::$FormField;
		$this->LabelType="sameasfield";
		$this->FieldId="";
		$this->DataSourceId=0;
		$this->Label="";
		$this->HideWhenEmpty=false;
		$this->FieldStyle="text";
		$this->LabelPosition="Top";
		$this->AddType("FieldId","String");
	}
}

