<?php


namespace rnpagebuilder\DTO\ManuallyCreated;


class DataSection
{
    public $Id;
    public $Label;
    public $PathId;
    /** @var string[] */
    public $Path;

    public function __construct($id='',$label='',$pathId='',$path=[])
    {
        $this->Id=$id;
        $this->Label=$label;
        $this->PathId=$pathId;
        $this->Path=$path;
    }
}