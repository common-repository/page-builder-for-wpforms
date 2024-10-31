<?php


namespace rnpagebuilder\PageBuilderOld\Renderers\Blocks\Core;


use rnpagebuilder\core\Exception\FriendlyException;
use rnpagebuilder\DTO\BlockTypeEnumDTO;
use rnpagebuilder\DTO\RNBlockBaseOptionsDTO;
use rnpagebuilder\PageBuilderOld\Renderers\Blocks\CalendarRenderer\CalendarRenderer;
use rnpagebuilder\PageBuilderOld\Renderers\Blocks\FormFieldBlockRenderer;
use rnpagebuilder\PageBuilderOld\Renderers\Blocks\FormRenderer\FormRenderer;
use rnpagebuilder\PageBuilderOld\Renderers\Blocks\GridRenderer\GridRenderer;
use rnpagebuilder\PageBuilderOld\Renderers\Blocks\ImageRenderer;
use rnpagebuilder\PageBuilderOld\Renderers\Blocks\ListRenderer\ListRenderer;
use rnpagebuilder\PageBuilderOld\Renderers\Blocks\NavigatorRenderer;
use rnpagebuilder\PageBuilderOld\Renderers\Blocks\SearchBarRenderer\SearchBarRenderer;
use rnpagebuilder\PageBuilderOld\Renderers\Blocks\TextRenderer;
use rnpagebuilder\PageBuilderOld\Renderers\ColumnRenderer;

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