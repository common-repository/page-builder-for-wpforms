<?php

namespace rnpagebuilder\PageGenerator\Blocks\Core;

use rnpagebuilder\DTO\BlockBaseOptionsDTO;
use rnpagebuilder\DTO\ColumnOptionsDTO;
use rnpagebuilder\PageGenerator\Core\RendererBase;

class Column extends RendererBase
{
    /** @var Row */
    public $Row;
    /** @var ColumnOptionsDTO */
    public $Options;
    /** @var BlockBase[] */
    public $Blocks;

    public function __construct($row,$columnOptions)
    {
        parent::__construct($row->loader);
        $this->Row=$row;
        $this->Options=$columnOptions;
        $this->Blocks=[];
        foreach($this->Options->Blocks as $currentBlock)
            $this->Blocks[]=BlockFactory::GetBlock($this,$currentBlock);

    }

    public function MaybeUpdateDataSource(){
        foreach($this->Blocks as $currentBlock)
            $currentBlock->MaybeUpdateDataSource();
    }

    protected function GetTemplateName()
    {
        return 'PageGenerator/Blocks/Core/Column.twig';
    }
}