<?php


namespace rnpagebuilder\PageBuilder\Renderers\Blocks;


use rnpagebuilder\DTO\FormFieldBlockOptionsDTO;
use rnpagebuilder\DTO\RNBlockBaseOptionsDTO;
use rnpagebuilder\PageBuilder\Renderers\Blocks\Core\BlockRendererBase;
use rnpagebuilder\PageBuilder\Renderers\Core\RendererBase;
use Twig\Markup;

class FormFieldBlockRenderer extends BlockRendererBase
{
    /** @var FormFieldBlockOptionsDTO */
    public $Options;
    protected function GetTemplateName()
    {
        $this->AddStyle('formfieldblock','PageBuilder/Renderers/Blocks/FormFieldBlockRenderer.css');
        return 'Blocks/FormFieldBlockRenderer.twig';
    }

    public function GetLabel(){
        if($this->Options->LabelType=='sameasfield')
        {
            $dataSource=$this->GetDefaultDataSource();
            if($dataSource==null)
                return '';

            return $dataSource->GetFieldLabel($this->Options->FieldId);
        }else{
            return $this->Options->Label;
        }
    }

    public function GetValue(){
        $dataSource=$this->GetDefaultDataSource();
        if($dataSource==null)
            return '';
        if($this->Options->FieldStyle=='similar')
        {
            $this->loader->AddStyle('Input','Styles/Input.css');
            return $dataSource->GetCurrentRowSimilarInput($this->Options->FieldId);
        }

        return new Markup($dataSource->GetCurrentRowHTMLValue($this->Options->FieldId),'UTF-8');
    }

}

