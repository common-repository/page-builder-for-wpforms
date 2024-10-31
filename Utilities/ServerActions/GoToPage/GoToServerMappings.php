<?php


namespace rnpagebuilder\Utilities\ServerActions\GoToPage;


use rnpagebuilder\DTO\core\StoreBase;

class GoToServerMappings extends StoreBase
{
    public $ParameterId;
    public $MappedToFieldId;
    public $MappedToPathId;
    public $Value='';

    public function LoadDefaultValues()
    {
        parent::LoadDefaultValues();
        $this->ParameterId=0;
        $this->MappedToPathId='';
        $this->MappedToFieldId='';
        $this->Value='';// TODO: Change the autogenerated stub
    }


}