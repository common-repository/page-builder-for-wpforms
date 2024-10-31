<?php

namespace rnpagebuilder\PageGenerator\Core\ClickActionRenderer;

use rnpagebuilder\core\Managers\SingleLineGenerator\SingleLineGenerator;
use rnpagebuilder\DTO\ClickActionOptionsDTO;
use rnpagebuilder\DTO\LinkTypeEnumDTO;
use rnpagebuilder\PageGenerator\Core\PageGenerator;
use rnpagebuilder\PageGenerator\Core\RendererBase;
use Twig\Markup;

class ClickActionRenderer extends RendererBase
{
    /** @var ClickActionOptionsDTO */
    public $Options;
    public $Child=null;
    public $URL='';
    /** @var PageGenerator */
    public $PageGenerator;
    /**
     * @param $pageGenerator PageGenerator
     * @param $options
     */
    public function __construct($pageGenerator,$options)
    {
        parent::__construct($pageGenerator->Loader);
        $this->PageGenerator=$pageGenerator;
        $this->Options=$options;
    }

    public function Render()
    {
        $generator=new SingleLineGenerator($this->PageGenerator);
        switch ($this->Options->LinkType)
        {
            case LinkTypeEnumDTO::$URL:
                $this->URL=$generator->GetText($this->Options->Value);
                break;
            case LinkTypeEnumDTO::$DisapproveEntry:
                $this->URL=$this->PageGenerator->GetDisapproveEntryURL();
                break;
            case LinkTypeEnumDTO::$ApproveEntry:
                $this->URL=$this->PageGenerator->GetApproveEntryURL();
                break;
            case LinkTypeEnumDTO::$EditEntry:
                $this->URL=$this->PageGenerator->GetEditEntryURL();
                break;
            case LinkTypeEnumDTO::$ViewEntry:
                $this->URL=$this->PageGenerator->GetViewEntryURL();
                break;
            case LinkTypeEnumDTO::$DeleteEntry:
                $this->URL=$this->PageGenerator->GetDeleteEntryURL();
                break;
            case LinkTypeEnumDTO::$MainPage:
                $this->URL=$this->PageGenerator->GetMainPageURL();
        }
        return parent::Render();
    }

    public function RenderWithChild($child)
    {
        return $this->Render();
    }

    public function SetHTMLChild($child)
    {
        $this->Child=new Markup($child,'UTF-8');
    }


    protected function GetTemplateName()
    {
        return 'PageGenerator/Core/ClickActionRenderer/ClickActionRenderer.twig';
    }
}