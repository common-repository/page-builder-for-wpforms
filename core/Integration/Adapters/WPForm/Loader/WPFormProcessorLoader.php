<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/19/2019
 * Time: 11:38 AM
 */

namespace rnpagebuilder\core\Integration\Adapters\WPForm\Loader;


use rnpagebuilder\core\Integration\Adapters\WPForm\Entry\WPFormEntryProcessor;
use rnpagebuilder\core\Integration\Adapters\WPForm\EntryEditor\WPFormEntryEditor;
use rnpagebuilder\core\Integration\Adapters\WPForm\FormProcessor\WPFormFormProcessor;
use rnpagebuilder\core\Integration\Processors\Loader\ProcessorLoaderBase;

class WPFormProcessorLoader extends ProcessorLoaderBase
{

    public function Initialize()
    {
        $this->FormProcessor=new WPFormFormProcessor($this->Loader);
        $this->EntryProcessor=new WPFormEntryProcessor($this->Loader);
        $this->EntryEditor=new WPFormEntryEditor($this->Loader);
    }
}