<?php


namespace rnpagebuilder\core\Integration\Processors\EntryEditor;


use rnpagebuilder\core\Loader;

abstract class EntryEditorBase
{
    /** @var Loader */
    public $Loader;
    public function __construct($loader)
    {
        $this->Loader=$loader;
    }

    public abstract function ExecuteShortCode($formId);

    public abstract function RenderForm($formId,$formRow=null);
}