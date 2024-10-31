<?php

namespace rnpagebuilder\PageGenerator\Blocks\FieldBlock;

use rnpagebuilder\core\Managers\UserManager;
use rnpagebuilder\DTO\FieldBlockOptionsDTO;
use rnpagebuilder\PageGenerator\Blocks\Core\BlockBase;
use rnpagebuilder\pr\Managers\InlineEditionManager\InlineEditionManager;
use Twig\Markup;

class FieldBlock extends BlockBase
{
    /** @var FieldBlockOptionsDTO */
    public $Options;

    protected function GetTemplateName()
    {
        return 'PageGenerator/Blocks/FieldBlock/FieldBlock.twig';
    }

    public function GetValue(){
        $userManager=new UserManager($this->GetLoader());
        if($this->Options->Editable&&$userManager->CurrentUserHasRole($this->Options->AllowedRoles))
        {
            return InlineEditionManager::RenderInlineEdition($this->Options->FieldId,$this->Options->PathId,$this->GetEntryRetriever());
        }

        return new Markup($this->GetEntryRetriever()->GetCurrentRowHtmlValue($this->Options->FieldId,$this->Options->PathId),"UTF-8");
    }
}