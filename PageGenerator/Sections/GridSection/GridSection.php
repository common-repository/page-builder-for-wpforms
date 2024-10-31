<?php

namespace rnpagebuilder\PageGenerator\Sections\GridSection;
use rnpagebuilder\DTO\GridSectionOptionsDTO;
use rnpagebuilder\PageGenerator\Core\Section\PageSectionBase;
use rnpagebuilder\PageGenerator\Sections\GridSection\Columns\Core\GridColumnBase;
use rnpagebuilder\PageGenerator\Sections\GridSection\Columns\Core\GridColumnFactory;

class GridSection extends PageSectionBase
{
    /** @var GridSectionOptionsDTO */
    public $Options;
    /** @var GridColumnBase[]  */
    public $Columns=[];
    public function __construct($area, $sectionOptions)
    {
        parent::__construct($area, $sectionOptions);
        $this->Columns=[];
        $this->Options=$sectionOptions;

        foreach ($this->Options->Columns as $currentColumn)
        {
            $this->Columns[]=GridColumnFactory::CreateGridColumn($this,$currentColumn);
        }

        if($this->Options->GridStyle!='')
            $this->loader->AddStyle('grid-style-'.$this->Options->GridStyle,'/PageGenerator/Sections/GridSection/Styles/WithBorders.css');

        if($this->Options->Stripped)
            $this->loader->AddStyle('grid-stripped','PageGenerator/Sections/GridSection/Styles/Stripped.css');
    }

    protected function GetTemplateName()
    {
        return 'PageGenerator/Sections/GridSection/GridSection.twig';
    }

    public function GetFieldOptionsSelector()
    {
        return '.Row'.$this->GetPageGenerator()->EntryRetriever->RowManager->CurrentRowIndex;
    }

    public function RowCount(){
        return $this->GetPageGenerator()->EntryRetriever->RowManager->RowCount();
    }

    public function GoNext(){
        $this->GetPageGenerator()->EntryRetriever->RowManager->GoNext();;
    }

    public function MaybeUpdateDataSource()
    {
        parent::MaybeUpdateDataSource();
        foreach($this->Columns as $column)
        {
            $column->MaybeUpdateDataSource();
        }
    }

    /**
     * @param $columnWidth GridColumnBase
     * @return string
     */
    public function GetWidth($columnWidth)
    {
        if(!is_numeric($columnWidth->Options->Width))
            return 'auto';

        return $columnWidth->Options->Width.'%';
    }


}