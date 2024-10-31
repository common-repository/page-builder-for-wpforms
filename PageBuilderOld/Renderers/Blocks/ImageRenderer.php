<?php


namespace rnpagebuilder\PageBuilderOld\Renderers\Blocks;


use rnpagebuilder\core\Exception\FriendlyException;
use rnpagebuilder\DTO\ComparisonTypeEnumDTO;
use rnpagebuilder\DTO\FieldTypeEnumDTO;
use rnpagebuilder\DTO\FormFieldBlockOptionsDTO;
use rnpagebuilder\DTO\ImageBlockOptionsDTO;
use rnpagebuilder\DTO\RNBlockBaseOptionsDTO;
use rnpagebuilder\DTO\RunnableSearchBarOptionsDTO;
use rnpagebuilder\DTO\RunnableSearchDateOptionsDTO;
use rnpagebuilder\DTO\RunnableSearchFieldBaseOptionsDTO;
use rnpagebuilder\DTO\RunnableSearchFieldDTO;
use rnpagebuilder\DTO\RunnableSearchMultipleOptionsDTO;
use rnpagebuilder\DTO\RunnableSearchTextOptionsDTO;
use rnpagebuilder\DTO\RunnableSearchUserOptionsDTO;
use rnpagebuilder\DTO\SearchBarBlockOptionsDTO;
use rnpagebuilder\PageBuilderOld\Renderers\Blocks\Core\BlockRendererBase;
use rnpagebuilder\PageBuilderOld\Renderers\ColumnRenderer;
use rnpagebuilder\PageBuilderOld\Renderers\Core\RendererBase;
use Twig\Markup;

class ImageRenderer extends BlockRendererBase
{
    /** @var ImageBlockOptionsDTO */
    public $Options;
    public function __construct(ColumnRenderer $columnRenderer, ImageBlockOptionsDTO $options,$dataSource)
    {
        parent::__construct($columnRenderer, $options,$dataSource);
    }


    public function GetImageURL(){
        if($this->Options->Src==null)
            return '';

        return $this->Options->Src->URL;
    }
    protected function GetTemplateName()
    {
        return 'Blocks/ImageRenderer.twig';
    }

    public function GetLabel(){

    }

}

