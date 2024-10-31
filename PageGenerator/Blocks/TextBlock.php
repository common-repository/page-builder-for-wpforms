<?php

namespace rnpagebuilder\PageGenerator\Blocks;

use rnpagebuilder\DTO\TextBlockOptionsDTO;
use rnpagebuilder\PageGenerator\Blocks\Core\BlockBase;
use rnpagebuilder\PageGenerator\TextRenderer\DocumentTextRenderer;

class TextBlock extends BlockBase
{
    /** @var TextBlockOptionsDTO */
    public $Options;
    /** @var DocumentTextRenderer */
    public $Document;
    public function __construct($column, $blockBaseOptions)
    {
        parent::__construct($column, $blockBaseOptions);

    }

    public function RenderText(){
        $this->Document=new DocumentTextRenderer($this->Options->Text,$this,null,$this->GetPageGenerator());
        $this->Document->Initialize();
        return $this->Document->Render();
    }

    protected function GetTemplateName()
    {
        return 'PageGenerator/Blocks/TextBlock.twig';
    }
}