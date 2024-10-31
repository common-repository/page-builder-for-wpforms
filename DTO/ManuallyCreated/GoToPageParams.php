<?php


namespace rnpagebuilder\DTO\ManuallyCreated;

class GoToPageParamsMapping{
    public $ParameterId;
    public $MappedToFieldId;
    public $MappedToPathId;
}

class GoToPageParams
{
    public $PageId;
    /** @var GoToPageParamsMapping[] */
    public $Mappings;
}