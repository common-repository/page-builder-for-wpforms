<?php

namespace rnpagebuilder\PageGenerator\Core\Area;

use rnpagebuilder\DTO\PageAreaBaseOptionsDTO;
use rnpagebuilder\DTO\PageSectionBaseOptionsDTO;
use rnpagebuilder\PageGenerator\Core\PageGenerator;
use rnpagebuilder\PageGenerator\Core\RendererBase;
use rnpagebuilder\PageGenerator\Core\Section\FormSection;
use rnpagebuilder\PageGenerator\Core\Section\PageSectionBase;
use rnpagebuilder\PageGenerator\Core\Section\PageSectionPerRow;
use rnpagebuilder\PageGenerator\Sections\GridSection\GridSection;
use rnpagebuilder\pr\PageGenerator\Sections\CalendarSection\CalendarSection;
use rnpagebuilder\pr\PageGenerator\Sections\CarouselSection\CarouselSection;

class PageAreaBase extends RendererBase
{
    /** @var PageGenerator */
    public $PageGenerator;
    /** @var PageAreaBaseOptionsDTO */
    public $Options;
    /** @var PageSectionBase[] */
    public $Sections;
    public function __construct($pageGenerator,$options)
    {
        parent::__construct($pageGenerator->Loader);
        $this->PageGenerator=$pageGenerator;
        $this->Options=$options;
        $this->CreateSections();
    }

    private function CreateSections()
    {
        foreach($this->Options->Sections as $currentSection)
            $this->Sections[]=$this->CreateSection($currentSection);
    }

    /**
     * @param $sectionOptions PageSectionBaseOptionsDTO
     */
    public function CreateSection($sectionOptions)
    {
        if($sectionOptions->Type=='Multiple')
            return new PageSectionPerRow($this,$sectionOptions);
        if($sectionOptions->Type=='FormArea')
            return new FormSection($this,$sectionOptions);
        if($sectionOptions->Type=='Grid')
            return new GridSection($this,$sectionOptions);
        if($sectionOptions->Type=='Calendar')
            return new CalendarSection($this,$sectionOptions);
        if($sectionOptions->Type=='Carousel')
            return new CarouselSection($this,$sectionOptions);


        return new PageSectionBase($this,$sectionOptions);
    }

    public function MaybeUpdateDataSource()
    {
        foreach($this->Sections as $currentSection)
            $currentSection->MaybeUpdateDataSource();
    }


    protected function GetTemplateName()
    {
        return 'PageGenerator/Core/Area/PageAreaBase.twig';
    }

    public function CanViewPage(){
        return true;
    }

}