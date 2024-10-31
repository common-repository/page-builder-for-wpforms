<?php

namespace rnpagebuilder\PageGenerator\Blocks\LinkBlock;

use rnpagebuilder\core\Managers\SingleLineGenerator\SingleLineGenerator;
use rnpagebuilder\DTO\FieldBlockOptionsDTO;
use rnpagebuilder\DTO\LinkBlockOptionsDTO;
use rnpagebuilder\DTO\LinkTypeEnumDTO;
use rnpagebuilder\PageGenerator\Blocks\Core\BlockBase;

class LinkBlock extends BlockBase
{
    /** @var LinkBlockOptionsDTO */
    public $Options;
    public $Text;
    public $URL;
    protected function GetTemplateName()
    {
        return 'PageGenerator/Blocks/LinkBlock/LinkBlock.twig';
    }

    protected function BeforeRender()
    {
        parent::BeforeRender();
        $pageGenerator=$this->GetPageGenerator();
        $generator=new SingleLineGenerator($pageGenerator);

        $this->Text=$generator->GetText($this->Options->Text);

        switch ($this->Options->LinkType)
        {
            case LinkTypeEnumDTO::$URL:
                $this->URL=$generator->GetText($this->Options->Value);
                break;
            case LinkTypeEnumDTO::$DisapproveEntry:
                $this->URL=$pageGenerator->GetDisapproveEntryURL();
                break;
            case LinkTypeEnumDTO::$ApproveEntry:
                $this->URL=$pageGenerator->GetApproveEntryURL();
                break;
            case LinkTypeEnumDTO::$EditEntry:
                $this->URL=$pageGenerator->GetEditEntryURL();
                break;
            case LinkTypeEnumDTO::$ViewEntry:
                $this->URL=$pageGenerator->GetViewEntryURL();
                break;
            case LinkTypeEnumDTO::$DeleteEntry:
                $this->URL=$pageGenerator->GetDeleteEntryURL();
                break;
            case LinkTypeEnumDTO::$MainPage:
                $this->URL=$pageGenerator->GetMainPageURL();
                break;
            case 'PDFBuilder':

                if(!function_exists('RNPDFBuilder'))
                {
                    return '';
                }

                $this->URL=RNPDFBuilder()->GetPDFURL($pageGenerator->EntryRetriever->GetCurrentEntryId(),
                    $this->Options->Value);


                break;
            case 'PDFImporter':

                if(!function_exists('RNPDFImporter'))
                {
                    return '';
                }

                $this->URL=RNPDFImporter()->GetPDFURL($pageGenerator->EntryRetriever->GetCurrentEntryId(),
                    $this->Options->Value);


                break;
        }

    }


}