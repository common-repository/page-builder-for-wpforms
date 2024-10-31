<?php

namespace rnpagebuilder\PageGenerator\Blocks\FieldSummary;

use rnpagebuilder\DTO\FieldBlockOptionsDTO;
use rnpagebuilder\PageGenerator\Blocks\Core\BlockBase;
use Twig\Markup;

class FieldSummary extends BlockBase
{
    public $Rows=[
    ];
    /** @var FieldBlockOptionsDTO */
    public $Options;

    protected function GetTemplateName()
    {
        return 'PageGenerator/Blocks/FieldSummary/FieldSummary.twig';
    }

    protected function BeforeRender()
    {
        parent::BeforeRender();

        foreach($this->GetPageGenerator()->EntryRetriever->FieldSettings as $currentField)
        {

            if(!$this->GetEntryRetriever()->GetCurrentRowFieldIsEmpty($currentField->Id))
            {
                $this->Rows[]=[
                    "Value"=>new Markup($this->GetEntryRetriever()->GetCurrentRowHtmlValue($currentField->Id,'Value'),"UTF-8"),
                    "Label"=>$currentField->Label
                ];
            }
        }
    }


}