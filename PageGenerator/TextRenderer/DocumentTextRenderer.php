<?php

namespace rnpagebuilder\PageGenerator\TextRenderer;

use rnpagebuilder\PageGenerator\TextRenderer\Core\ITextRendererParent;
use rnpagebuilder\PageGenerator\TextRenderer\Core\TextRendererBase;

class DocumentTextRenderer extends TextRendererBase
{
    public $AdditionalOptions=null;

    /**
     * @param $content
     * @param $parent ITextRendererParent
     * @param $additionalOptions
     * @param $pageGenerator
     */
    public function __construct($content, $parent = null,$additionalOptions=null,$pageGenerator=null)
    {
        $this->AdditionalOptions=$additionalOptions;

        parent::__construct($content, $parent,$pageGenerator);
    }

    public function GetAdditionalOptionValue($name,$defaultValue='')
    {
        if($this->AdditionalOptions==null||!isset($this->AdditionalOptions->$name))
            return $defaultValue;

        return $this->AdditionalOptions->$name;
    }

    protected function GetTemplateName()
    {
        return 'PageGenerator/TextRenderer/DocumentTextRenderer.twig';
    }

}