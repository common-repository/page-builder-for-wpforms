<?php

namespace rnpagebuilder\PageGenerator\Core\Section;

use rnpagebuilder\DTO\PageSectionBaseOptionsDTO;
use rnpagebuilder\PageGenerator\Blocks\Core\IRowContainer;
use rnpagebuilder\PageGenerator\Blocks\Core\Row;
use rnpagebuilder\PageGenerator\Core\Area\PageAreaBase;
use rnpagebuilder\PageGenerator\Core\RendererBase;

class PageSectionBase extends RendererBase implements IRowContainer
{
    /** @var PageAreaBase */
    public $Area;
    /** @var PageSectionBaseOptionsDTO */
    public $Options;
    /** @var Row[] */
    public $Rows;
    /**
     * @param $area PageAreaBase
     * @param $sectionOptions PageSectionBaseOptionsDTO
     */
    public function __construct($area,$sectionOptions)
    {
        parent::__construct($area->PageGenerator->Loader);
        $this->Area=$area;
        $this->Options=$sectionOptions;
        $this->Rows=[];
        foreach($this->Options->Rows as $currentRow)
        {
            $this->Rows[]=new Row($this,$currentRow);
        }
    }

    public function MaybeUpdateDataSource()
    {
        foreach($this->Rows as $currentRow)
            $currentRow->MaybeUpdateDataSource();
    }

    public function GetEntryRetriever(){
        return $this->Area->PageGenerator->EntryRetriever;
    }

    public function GetFieldOptionsSelector()
    {
        return '';
    }

    protected function GetTemplateName()
    {
        return 'PageGenerator/Core/Section/PageSectionBase.twig';
    }

    public function GetLoader()
    {
        return $this->loader;
    }

    public function GetPageGenerator()
    {
        return $this->Area->PageGenerator;
    }

    public function BeforeRender(){
        $this->GetPageGenerator()->RenderingSection=$this;
    }

}