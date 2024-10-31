<?php


namespace rnpagebuilder\PageBuilderOld\Renderers\Blocks\SearchBarRenderer\SearchFieldTemplates;


use rnpagebuilder\DTO\SearchFieldOptionsDTO;
use rnpagebuilder\PageBuilderOld\Renderers\Blocks\SearchBarRenderer\SearchFieldRenderer;
use rnpagebuilder\PageBuilderOld\Renderers\Core\RendererBase;

abstract class SearchFieldTemplateBase extends RendererBase
{
    /** @var SearchFieldRenderer */
    public $Field;
    public function __construct($loader, $twig,$field)
    {
        parent::__construct($loader, $twig);
        $this->Field=$field;
    }

    public abstract function MaybeUpdateDataSource();

}