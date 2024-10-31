<?php

namespace rnpagebuilder\PageGenerator\Sections\GridSection\Columns;

use rnpagebuilder\DTO\CustomCellTemplateOptionsDTO;
use rnpagebuilder\DTO\TextCellTemplateOptionsDTO;
use rnpagebuilder\PageGenerator\Blocks\Core\IRowContainer;
use rnpagebuilder\PageGenerator\Blocks\Core\Row;
use rnpagebuilder\PageGenerator\Sections\GridSection\Columns\Core\GridColumnBase;
use rnpagebuilder\PageGenerator\TextRenderer\DocumentTextRenderer;
use Twig\Markup;

class CustomColumn extends GridColumnBase implements IRowContainer
{
    /** @var CustomCellTemplateOptionsDTO */
    public $Options;

    /** @var Row[] */
    public $Rows;

    public function __construct($section, $options)
    {
        parent::__construct($section, $options);
        $this->Rows=[];
        foreach($this->Options->Rows as $currentRow)
        {
            $this->Rows[]=new Row($this,$currentRow);
        }
    }


    public function Render()
    {
        $text='';
        foreach($this->Rows as $currentRow)
            $text.=$currentRow->Render();
        return new Markup($text,'UTF-8');
    }


    public function GetPageGenerator()
    {
        return $this->Section->GetPageGenerator();
    }
}