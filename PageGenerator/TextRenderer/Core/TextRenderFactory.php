<?php

namespace rnpagebuilder\PageGenerator\TextRenderer\Core;


use rnpagebuilder\PageGenerator\TextRenderer\FieldTextRenderer;
use rnpagebuilder\PageGenerator\TextRenderer\IconTextRenderer;
use rnpagebuilder\PageGenerator\TextRenderer\Table\TableRenderer;
use rnpagebuilder\PageGenerator\TextRenderer\TextTextRenderer;

class TextRenderFactory
{

    public static function GetRenderer($content,$parent,$pageGenerator)
    {
        if($content==null)
            return null;

        if(!isset($content->type))
            throw new \Exception('This renderer does not have a type');

        switch ($content->type)
        {
            case 'heading':
                return new SimpleContainerTextRenderer('h'.$content->attrs->level,$content,$parent,$pageGenerator);
            case 'text':
                return new TextTextRenderer($content,$parent,$pageGenerator);
            case 'field':
                return new FieldTextRenderer($content,$parent,$pageGenerator);
            case 'paragraph':
                return new SimpleContainerTextRenderer('p',$content,$parent,$pageGenerator);
            case 'icon':
                return new IconTextRenderer($content,$parent,$pageGenerator);
            case 'table':
                return new SimpleContainerTextRenderer('table',$content,$parent,$pageGenerator);
            case 'table_row':
                return new SimpleContainerTextRenderer('tr',$content,$parent,$pageGenerator);
            case 'table_cell':
                return new SimpleContainerTextRenderer('td',$content,$parent,$pageGenerator);
            default:
                throw new \Exception('No text renderer found for type '.$content->type);
        }
    }

}