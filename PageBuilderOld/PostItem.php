<?php


namespace rnpagebuilder\PageBuilderOld;


class PostItem
{
    public $Name;
    public $Value;
    public function __construct($name,$value)
    {
        $this->Name=$name;
        $this->Value=$value;
    }

    public function Serialize(){
        return json_encode($this->Value);

    }

}