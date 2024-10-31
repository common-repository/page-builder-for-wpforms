<?php


namespace rnpagebuilder\PageBuilder\Renderers\Blocks;


use rnpagebuilder\DTO\FormFieldBlockOptionsDTO;
use rnpagebuilder\DTO\RNBlockBaseOptionsDTO;
use rnpagebuilder\PageBuilder\Renderers\Blocks\Core\BlockRendererBase;
use rnpagebuilder\PageBuilder\Renderers\Core\RendererBase;
use Twig\Markup;

class FormFieldBockRenderer extends BlockRendererBase
{
    /** @var FormFieldBlockOptionsDTO */
    public $Options;
    protected function GetTemplateName()
    {
        return 'Blocks/FormFieldBockRenderer.twig';
    }

    public function GetLabel(){
        if($this->Options->LabelType=='sameasfield')
        {
            $dataSource=$this->GetDataSourceById($this->Options->DataSourceId);
            if($dataSource==null)
                return '';

            return $dataSource->GetFieldLabel($this->Options->FieldId);
        }else{
            return $this->Options->Label;
        }
    }

    public function GetValue(){
        $dataSource=$this->GetDataSourceById($this->Options->DataSourceId);
        if($dataSource==null)
            return '';
        if($this->Options->FieldStyle=='similar')
            return $dataSource->GetCurrentRowSimilarInput($this->Options->FieldId);

        return new Markup(nl2br(esc_html($dataSource->GetCurrentRowStringValue($this->Options->FieldId))),'UTF-8');
    }

}

