<?php

namespace rnpagebuilder\PageGenerator\Blocks\Core;

use rnpagebuilder\DTO\RowOptionsDTO;
use rnpagebuilder\PageGenerator\Core\RendererBase;

class Row extends RendererBase
{
    /** @var IRowContainer */
    public $Parent;
    /** @var RowOptionsDTO */
    public $Options;
    /** @var Column[] */
    public $Columns;
    public function __construct($rowContainer,$rowOptions)
    {
        parent::__construct($rowContainer->GetLoader());
        $this->Parent=$rowContainer;
        $this->Options=$rowOptions;

        foreach($this->Options->Columns as $columnOptions)
        {
            $this->Columns[]=new Column($this,$columnOptions);
        }

    }

    public function MaybeUpdateDataSource(){
        foreach($this->Columns as $currentColumn)
            $currentColumn->MaybeUpdateDataSource();
    }


    protected function GetTemplateName()
    {
        return 'PageGenerator/Blocks/Core/Row.twig';
    }
}