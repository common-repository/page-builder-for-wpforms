<?php

namespace rnpagebuilder\PageGenerator\Sections\GridSection\Columns\Core;

use rnpagebuilder\DTO\GridColumnBaseOptionsDTO;
use rnpagebuilder\PageBuilderOld\DataSources\Core\ColumnBase;
use rnpagebuilder\PageGenerator\Sections\GridSection\Columns\CustomColumn;
use rnpagebuilder\PageGenerator\Sections\GridSection\Columns\FieldColumn;
use rnpagebuilder\PageGenerator\Sections\GridSection\Columns\LinkColumn;
use rnpagebuilder\PageGenerator\Sections\GridSection\Columns\TextColumn;
use rnpagebuilder\PageGenerator\Sections\GridSection\GridSection;

class GridColumnFactory
{
    /**
     * @param $parent GridSection
     * @param $options GridColumnBaseOptionsDTO
     * @return GridColumnBase
     */
    public static function CreateGridColumn($parent,$options)
    {
        switch ($options->Type)
        {
            case 'Link':
                return new LinkColumn($parent,$options);
            case 'Field':
                return new FieldColumn($parent,$options);
            case 'Text':
                return new TextColumn($parent,$options);
            case 'Custom':
                return new CustomColumn($parent,$options);
            default:
                throw new \Exception("Invalid column type ".$options->Type);
        }
    }
}