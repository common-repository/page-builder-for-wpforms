<?php

namespace rnpagebuilder\PageGenerator\Sections\GridSection\Columns;

use rnpagebuilder\DTO\TextCellTemplateOptionsDTO;
use rnpagebuilder\PageGenerator\Sections\GridSection\Columns\Core\GridColumnBase;
use rnpagebuilder\PageGenerator\TextRenderer\DocumentTextRenderer;

class TextColumn extends GridColumnBase
{
    /** @var TextCellTemplateOptionsDTO */
    public $Options;



    public function Render()
    {
        $this->Document=new DocumentTextRenderer($this->Options->Text,$this,null,$this->Section->GetPageGenerator());
        $this->Document->Initialize();
        return $this->Document->Render();
    }


}