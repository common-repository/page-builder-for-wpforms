<?php

namespace rnpagebuilder\PageGenerator\Sections\GridSection\Columns\Core;

use rnpagebuilder\DTO\GridColumnBaseOptionsDTO;
use rnpagebuilder\PageGenerator\Sections\GridSection\GridSection;
use rnpagebuilder\PageGenerator\TextRenderer\Core\ITextRendererParent;

abstract class GridColumnBase implements ITextRendererParent
{
    /** @var GridColumnBaseOptionsDTO */
    public $Options;
    /** @var GridSection */
    public $Section;
    public function __construct($section,$options)
    {
        $this->Section=$section;
        $this->Options=$options;
    }

    public function GetHeader(){
        return $this->Options->Header;
    }

    public function GetLoader()
    {
        return $this->Section->loader;
    }

    public abstract function Render();
    public function MaybeUpdateDataSource(){

    }
}