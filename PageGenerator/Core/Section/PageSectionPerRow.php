<?php

namespace rnpagebuilder\PageGenerator\Core\Section;

use rnpagebuilder\DTO\MultiplePageAreaOptionsDTO;
use rnpagebuilder\PageGenerator\TextRenderer\Core\ITextRendererParent;
use rnpagebuilder\PageGenerator\TextRenderer\DocumentTextRenderer;
use Twig\Markup;

class PageSectionPerRow extends PageSectionBase implements ITextRendererParent
{
    public $WidthToUse='';
    protected function GetTemplateName()
    {
        return "PageGenerator/Core/Section/PageSectionPerRow.twig";
    }

    public function Render()
    {
        $this->GetPageGenerator()->EntryRetriever->RowManager->Reset();
        /** @var MultiplePageAreaOptionsDTO $areaOptions */
        $areaOptions=$this->Area->Options;

        $this->WidthToUse=trim($areaOptions->ItemWidth);
        if($this->WidthToUse!='')
        {
            if(!preg_match('/[0-9]*\.?[0-9]+(px|%)?/',$this->WidthToUse))
                $this->WidthToUse='';
        }

        return parent::Render();
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



    public function GetEmptyMessage(){
        /** @var MultiplePageAreaOptionsDTO $options */
        $options=$this->GetPageGenerator()->Area->Options;

        $document=new DocumentTextRenderer($options->EmptyMessage,$this,null,$this->GetPageGenerator());
        $document->Initialize();
        return new Markup('<div class="rnRow"><div class="rnColumn"><div style="padding: 5px">'.$document->Render().'</div></div></div>','UTF-8');
    }

    public function GetItemStyles(){
        $styles='';
        if($this->WidthToUse=='')
        {
            $styles='width:100%;';
        }else{
            $styles='width:'.$this->WidthToUse.';';
        }

        return $styles;

    }





}