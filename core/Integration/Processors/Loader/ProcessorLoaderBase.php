<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/19/2019
 * Time: 11:37 AM
 */

namespace rnpagebuilder\core\Integration\Processors\Loader;


use rnpagebuilder\core\core\Loader;
use rnpagebuilder\core\Integration\Processors\Entry\EntryProcessorBase;
use rnpagebuilder\core\Integration\Processors\EntryEditor\EntryEditorBase;
use rnpagebuilder\core\Integration\Processors\FormProcessor\FormProcessorBase;

abstract class ProcessorLoaderBase
{
    /** @var Loader */
    public $Loader;
    /** @var FormProcessorBase */
    public $FormProcessor;
    /** @var EntryProcessorBase */
    public $EntryProcessor;
    /** @var EntryEditorBase */
    public $EntryEditor;
    public function __construct($loader)
    {
        $this->Loader=$loader;
    }


    public abstract function Initialize();
}

