<?php

namespace rnpagebuilder\PageGenerator\Blocks;

use rnpagebuilder\DTO\InnerSectionBlockOptionsDTO;
use rnpagebuilder\PageGenerator\Blocks\Core\BlockBase;
use rnpagebuilder\PageGenerator\Blocks\Core\IRowContainer;
use rnpagebuilder\PageGenerator\Blocks\Core\Row;

class InnerSectionBlock extends BlockBase implements IRowContainer
{
    /** @var InnerSectionBlockOptionsDTO */
    public $Options;
    /** @var Row[] */
    public $Rows;

    public function __construct($column, $blockBaseOptions)
    {
        parent::__construct($column, $blockBaseOptions);
        $this->Rows=[];
        foreach($this->Options->Rows as $currentRow)
        {
            $this->Rows[]=new Row($this,$currentRow);
        }
    }


    protected function GetTemplateName()
    {
        return 'PageGenerator/Blocks/InnerSectionBlock.twig';
    }

    public function GetLoader()
    {
        return $this->GetPageGenerator()->Loader;
    }
}