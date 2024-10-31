<?php

namespace rnpagebuilder\PageGenerator\Core\AlertManager;

use rnpagebuilder\PageGenerator\TextRenderer\Core\ITextRendererParent;
use rnpagebuilder\PageGenerator\TextRenderer\DocumentTextRenderer;
use Twig\Markup;

class AlertManager
{
    /**
     * @param $content
     * @param $parent ITextRendererParent
     * @param $additionalOptions
     * @param $pageGenerator
     */
    public static function GenerateError($content, $parent = null,$additionalOptions=null,$pageGenerator=null)
    {

        $parent->GetLoader()->AddStyle('AlertErrorMessage','PageGenerator/Core/AlertManager/AlertError.css');
        return strval(new Markup('<div class="errorMessage">'.self::GenerateText($content,$parent,$additionalOptions,$pageGenerator).'</div>','UTF-8'));
    }

    private static function GenerateText($content, $parent = null,$additionalOptions=null,$pageGenerator=null){
        if(is_string($content))
            return $content;
        $document=new DocumentTextRenderer($content,$parent,$additionalOptions,$pageGenerator);
        $document->Initialize();
        return $document->Render();
    }
}