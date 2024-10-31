<?php

namespace rnpagebuilder\PageGenerator\Sections\GridSection\Columns;

use rnpagebuilder\core\Managers\SingleLineGenerator\SingleLineGenerator;
use rnpagebuilder\DTO\LinkCellTemplateOptionsDTO;
use rnpagebuilder\DTO\LinkTypeEnumDTO;
use rnpagebuilder\PageGenerator\Sections\GridSection\Columns\Core\GridColumnBase;
use Twig\Markup;

class LinkColumn extends GridColumnBase
{
    /** @var LinkCellTemplateOptionsDTO */
    public $Options;


    public function Render()
    {
        $singleGenerator=new SingleLineGenerator($this->Section->GetPageGenerator());

        $url='';
        $target='';
        if($this->Options->OpenInNewTab)
            $target='target="_blank"';
        $text= $singleGenerator->GetText($this->Options->Text);
        switch($this->Options->LinkType)
        {
            case LinkTypeEnumDTO::$DeleteEntry:
                return new Markup('<a '.$target.'  href="'.esc_attr__($this->Section->GetPageGenerator()->GetDeleteEntryURL()).'">'.esc_html($text).'</a>','UTF-8');
            case LinkTypeEnumDTO::$ApproveEntry:
                return new Markup('<a '.$target.'  href="'.esc_attr__($this->Section->GetPageGenerator()->GetApproveEntryURL()).'">'.esc_html($text).'</a>','UTF-8');
            case LinkTypeEnumDTO::$DisapproveEntry:
                return new Markup('<a '.$target.'  href="'.esc_attr__($this->Section->GetPageGenerator()->GetDisapproveEntryURL()).'">'.esc_html($text).'</a>','UTF-8');
            case LinkTypeEnumDTO::$EditEntry:
                return new Markup('<a '.$target.'  href="'.esc_attr__($this->Section->GetPageGenerator()->GetEditEntryURL()).'">'.esc_html($text).'</a>','UTF-8');
            case LinkTypeEnumDTO::$ViewEntry:
                return new Markup('<a '.$target.' href="'.esc_attr__($this->Section->GetPageGenerator()->GetViewEntryURL()).'">'.esc_html($text).'</a>','UTF-8');
            case LinkTypeEnumDTO::$URL:
                if(filter_var($this->Options->Value,FILTER_VALIDATE_URL))
                    $url=$this->Options->Value;
                return new Markup('<a '.$target.' href="'.esc_attr__($url).'">'.esc_html($text).'</a>','UTF-8');
            case LinkTypeEnumDTO::$MainPage:
                return new Markup('<a '.$target.' href="'.esc_attr__($this->Section->GetPageGenerator()->GetMainPageURL()).'">'.esc_html($text).'</a>','UTF-8');
            case 'PDFBuilder':

                if(!function_exists('RNPDFBuilder'))
                {
                    return '';
                }

                return new Markup('<a '.$target.' href="'.esc_attr__($pdfBuilderTemplates=RNPDFBuilder()->GetPDFURL($this->Section->GetPageGenerator()->EntryRetriever->GetCurrentEntryId(),
                        $this->Options->Value)).'">'.esc_html($text).'</a>','UTF-8');


                break;
            case 'PDFImporter':

                if(!function_exists('RNPDFImporter'))
                {
                    return '';
                }

                return new Markup('<a '.$target.' href="'.esc_attr__($pdfBuilderTemplates=RNPDFImporter()->GetPDFURL($this->Section->GetPageGenerator()->EntryRetriever->GetCurrentEntryId(),
                        $this->Options->Value)).'">'.esc_html($text).'</a>','UTF-8');


                break;
        }


    }
}