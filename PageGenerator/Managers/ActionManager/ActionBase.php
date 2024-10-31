<?php

namespace rnpagebuilder\PageGenerator\Managers\ActionManager;

use rnpagebuilder\PageGenerator\Core\PageGenerator;
use rnpagebuilder\PageGenerator\Managers\MessageManager;

abstract class ActionBase
{
    /** @var PageGenerator */
    public $PageGenerator;
    public function __construct($pageGenerator)
    {
        $this->PageGenerator=$pageGenerator;
    }

    public function GetRef(){
        return $this->PageGenerator->GetGetParameter('ref');
    }

    public function IsValid(){
        $nonce=$this->PageGenerator->GetGetParameter('rnactionnonce');
        $action=$this->PageGenerator->Options->Id.'_'.$this->GetActionId().'_'.$this->GetRef();
        if(!wp_verify_nonce($nonce,$action))
        {
            return false;
        }

        return true;
    }

    public abstract function GetActionId();
    public abstract function Execute();

}