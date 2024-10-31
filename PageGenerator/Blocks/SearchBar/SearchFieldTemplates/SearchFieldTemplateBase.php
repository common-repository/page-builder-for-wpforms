<?php


namespace rnpagebuilder\PageGenerator\Blocks\SearchBar\SearchFieldTemplates;



use rnpagebuilder\PageGenerator\Blocks\SearchBar\SearchField;
use rnpagebuilder\PageGenerator\Core\RendererBase;

abstract class SearchFieldTemplateBase extends RendererBase
{
    /** @var SearchField */
    public $Field;
    public function __construct($loader, $twig,$field)
    {
        parent::__construct($loader, $twig);
        $this->Field=$field;
    }

    public abstract function MaybeUpdateDataSource();

}