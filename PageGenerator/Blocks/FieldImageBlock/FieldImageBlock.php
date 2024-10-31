<?php

namespace rnpagebuilder\PageGenerator\Blocks\FieldImageBlock;

use rnpagebuilder\DTO\FieldBlockOptionsDTO;
use rnpagebuilder\DTO\FieldImageBlockOptionsDTO;
use rnpagebuilder\PageGenerator\Blocks\Core\BlockBase;
use rnpagebuilder\PageGenerator\Core\ClickActionRenderer\ClickActionRenderer;
use rnpagebuilder\Utilities\Sanitizer;

class FieldImageBlock extends BlockBase
{
    /** @var FieldImageBlockOptionsDTO */
    public $Options;
    public $IsClickable=false;
    public $ClickURL='';
    /** @var ClickActionRenderer */
    public $ClickActionRenderer;
    protected function GetTemplateName()
    {
        return 'PageGenerator/Blocks/FieldImageBlock/FieldImageBlock.twig';
    }

    protected function BeforeRender()
    {
        parent::BeforeRender();
        $this->IsClickable=$this->Options->IsClickable;

        if($this->IsClickable)
        {
            $this->ClickActionRenderer = new ClickActionRenderer($this->GetPageGenerator(), $this->Options->ClickAction);
            $this->ClickActionRenderer->SetHTMLChild('<img src="'.esc_attr($this->GetImageURL()).'"/>');
        }
    }


    public function GetImageURL(){
        $image= $this->GetEntryRetriever()->GetCurrentRowValue($this->Options->FieldId,null,['value_raw']);
         return Sanitizer::GetStringValueFromPath($image,[0,'value']);


    }
}