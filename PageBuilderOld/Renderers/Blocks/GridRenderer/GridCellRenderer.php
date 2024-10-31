<?php


namespace rnpagebuilder\PageBuilderOld\Renderers\Blocks\GridRenderer;


use rnpagebuilder\core\Utils\ObjectSanitizer;
use rnpagebuilder\DTO\ClickActionEnumDTO;
use rnpagebuilder\DTO\FormFieldBlockOptionsDTO;
use rnpagebuilder\DTO\GridColumnOptionsDTO;
use rnpagebuilder\DTO\GridFieldBlockOptionsDTO;
use rnpagebuilder\DTO\RNBlockBaseOptionsDTO;
use rnpagebuilder\DTO\TextFieldBlockOptionsDTO;
use rnpagebuilder\HtmlGenerator\HtmlGenerator;
use rnpagebuilder\PageBuilderOld\Renderers\Blocks\Core\BlockRendererBase;
use rnpagebuilder\PageBuilderOld\Renderers\ColumnRenderer;
use rnpagebuilder\PageBuilderOld\Renderers\Core\RendererBase;
use rnpagebuilder\SlateGenerator\SlateTextGenerator\SlateTextGenerator;
use rnpagebuilder\Utilities\PageUtilities;
use rnpagebuilder\Utilities\Sanitizer;
use rnpagebuilder\Utilities\ServerActions\GoToPage\GoToServerAction;
use Twig\Markup;

class GridCellRenderer extends RendererBase
{
    /** @var GridColumnOptionsDTO */
    public $Options;
    /** @var GridRowRenderer  */
    public $Row;
    public function __construct(GridRowRenderer $gridRowRenderer, GridColumnOptionsDTO $column)
    {
        parent::__construct($gridRowRenderer->loader,$gridRowRenderer->twig);
        $this->Row=$gridRowRenderer;
        $this->Options=$column;
    }


    protected function GetTemplateName()
    {
        return 'Blocks/GridRenderer/GridCellRenderer.twig';
    }

    public function GetLabel(){
        return '';
    }

    public function GetValue(){
        switch ($this->Options->ContentType)
        {
            case 'field':

                if($this->Row->RowData==null)
                    return '';

                $field=ObjectSanitizer::Sanitize($this->Options->Content,(object)array(
                   "FieldId"=>'',
                   "PathId"=>'',
                    "Path"=>[]
                ));

                if($field==null)
                    return '';
                return $this->MaybeWrapContent($this->Row->RowData->GetHTMLValue($field->FieldId,$field->PathId));
            case 'text':
                return $this->MaybeWrapContent((new SlateTextGenerator($this->loader,$this->Row->Grid->GetPageRenderer()))->GetText($this->Options->Content,$this->Row->RowData));
            case 'html':
                return $this->MaybeWrapContent((new HtmlGenerator($this->loader,$this->Row->Grid->GetPageRenderer()))->GetText($this->Options->Content,$this->Row->RowData));


        }
    }

    private function MaybeWrapContent($value)
    {
        if(!$this->Options->IsClickable)
        {
            return $value;
        }

        $m=new Markup('a','UTF-8');

        if(!$value instanceof Markup)
            $value=esc_html($value);

        $url='#';
        if($this->Options->ClickAction==ClickActionEnumDTO::$OpenURL)
        {
            $url=''.esc_attr((new SlateTextGenerator($this->loader,$this->Row->Grid->GetPageRenderer()))->GetText($this->Options->ClickParams)).'';
        }else{
            $pageUtilities=new PageUtilities($this->Row->Grid->GetPageRenderer());
            $goToPageAction=new GoToServerAction();
            $goToPageAction->Merge($this->Options->ClickParams);
            if($goToPageAction->PageId!=0)
            {
                $goToPageAction->Initialize($this->Row->RowData);
                $url = $pageUtilities->CreateLink([$goToPageAction]);
            }
        }

        return new Markup('<a href="'.$url.'" target="'.esc_html($this->Options->ClickTarget).'">'.$value.'</a>','UTF-8');
    }

}

