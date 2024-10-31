<?php


namespace rnpagebuilder\PageBuilder\Renderers\Blocks\FormRenderer;


use rnpagebuilder\PageBuilder\Renderers\Blocks\Core\BlockRendererBase;
use Twig\Markup;

class FormRenderer extends BlockRendererBase
{


    protected function GetTemplateName()
    {
        // TODO: Implement GetTemplateName() method.
    }

    public function Render()
    {
        $defaultDataSource= $this->GetDefaultDataSource();
        if($defaultDataSource==null)
            return '';

        $form=$this->loader->ProcessorLoader->EntryEditor;



        $formId=$defaultDataSource->GetOriginalId();
        if($formId==null)
            return '';

        $content=$form->RenderForm($formId,$this->GetCurrentRow());
        $content= new Markup($content,'UTF-8');
        return $content;

    }


}