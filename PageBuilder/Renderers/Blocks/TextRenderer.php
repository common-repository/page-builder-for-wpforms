<?php


namespace rnpagebuilder\PageBuilder\Renderers\Blocks;


use rnpagebuilder\DTO\FormFieldBlockOptionsDTO;
use rnpagebuilder\DTO\RNBlockBaseOptionsDTO;
use rnpagebuilder\DTO\TextFieldBlockOptionsDTO;
use rnpagebuilder\PageBuilder\Renderers\Blocks\Core\BlockRendererBase;
use rnpagebuilder\PageBuilder\Renderers\Core\RendererBase;
use rnpagebuilder\SlateGenerator\SlateHTMLGenerator\SlateHTMLGenerator;
use rnpagebuilder\SlateGenerator\SlateTextGenerator\SlateTextGenerator;
use Twig\Markup;

class TextRenderer extends BlockRendererBase
{
    /** @var TextFieldBlockOptionsDTO */
    public $Options;
    protected function GetTemplateName()
    {
        return 'Blocks/TextRenderer.twig';
    }

    public function GetLabel(){
        return $this->Options->Label;
    }

    public function GetValue(){
        $slate=new SlateHTMLGenerator($this->loader,$this->GetPageRenderer());
        return $slate->GetHTML($this->Options->Text);
    }

}

