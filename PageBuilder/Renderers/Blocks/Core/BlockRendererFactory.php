<?php


namespace rnpagebuilder\PageBuilder\Renderers\Blocks\Core;


use rnpagebuilder\core\Exception\FriendlyException;
use rnpagebuilder\DTO\BlockTypeEnumDTO;
use rnpagebuilder\DTO\RNBlockBaseOptionsDTO;
use rnpagebuilder\PageBuilder\Renderers\Blocks\CalendarRenderer\CalendarRenderer;
use rnpagebuilder\PageBuilder\Renderers\Blocks\FormFieldBlockRenderer;
use rnpagebuilder\PageBuilder\Renderers\Blocks\FormRenderer\FormRenderer;
use rnpagebuilder\PageBuilder\Renderers\Blocks\GridRenderer\GridRenderer;
use rnpagebuilder\PageBuilder\Renderers\Blocks\ImageRenderer;
use rnpagebuilder\PageBuilder\Renderers\Blocks\ListRenderer\ListRenderer;
use rnpagebuilder\PageBuilder\Renderers\Blocks\NavigatorRenderer;
use rnpagebuilder\PageBuilder\Renderers\Blocks\SearchBarRenderer\SearchBarRenderer;
use rnpagebuilder\PageBuilder\Renderers\Blocks\TextRenderer;
use rnpagebuilder\PageBuilder\Renderers\ColumnRenderer;

class BlockRendererFactory
{
    public static function GetRenderer(ColumnRenderer $columnRenderer, RNBlockBaseOptionsDTO $options,$dataSource=null){
        switch ($options->Type)
        {
            case BlockTypeEnumDTO::$FormField:
                return new FormFieldBlockRenderer($columnRenderer,$options,$dataSource);
            case BlockTypeEnumDTO::$Text:
                return new TextRenderer($columnRenderer,$options,$dataSource);
            case BlockTypeEnumDTO::$Navigator:
                return new NavigatorRenderer($columnRenderer,$options,$dataSource);
            case BlockTypeEnumDTO::$Grid:
                return new GridRenderer($columnRenderer,$options,$dataSource);
            case BlockTypeEnumDTO::$SearchBar:
                return new SearchBarRenderer($columnRenderer,$options,$dataSource);
            case BlockTypeEnumDTO::$Image:
                return new ImageRenderer($columnRenderer,$options,$dataSource);
            case BlockTypeEnumDTO::$List:
                return new ListRenderer($columnRenderer,$options,$dataSource);
            case BlockTypeEnumDTO::$Form:
                return new FormRenderer($columnRenderer,$options,$dataSource);
            case BlockTypeEnumDTO::$Calendar:
                return new CalendarRenderer($columnRenderer,$options,$dataSource);
            default:
                throw new FriendlyException('Undefined renderer for '.$options->Type);
        }
    }

}