<?php

namespace rnpagebuilder\DTO\core\Factories;

use rnpagebuilder\DTO\CustomCellTemplateOptionsDTO;
use rnpagebuilder\DTO\FieldCellTemplateOptionsDTO;
use rnpagebuilder\DTO\LinkCellTemplateOptionsDTO;
use rnpagebuilder\DTO\TextCellTemplateOptionsDTO;

class CellTemplateFactory
{
    public static function GetCellTemplateOptions($value)
    {
        switch ($value->Type)
        {
            case 'Link':
                return (new LinkCellTemplateOptionsDTO())->Merge($value);
            case 'Field':
                return (new FieldCellTemplateOptionsDTO())->Merge($value);
            case 'Text':
                return (new TextCellTemplateOptionsDTO())->Merge($value);
            case 'Custom':
                return (new CustomCellTemplateOptionsDTO())->Merge($value);
        }
    }

    public static function GetOptionsFromArray($value)
    {
        if($value==null)
            return [];

        $newList=[];
        foreach($value as $currentValue)
        {
            if($currentValue==null)
                continue;
            $newList[] = CellTemplateFactory::GetCellTemplateOptions($currentValue);
        }
        return $newList;
    }
}