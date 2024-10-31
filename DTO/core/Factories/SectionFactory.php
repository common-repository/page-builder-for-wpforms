<?php

namespace rnpagebuilder\DTO\core\Factories;

use rnpagebuilder\DTO\GridSectionOptionsDTO;
use rnpagebuilder\DTO\StandardSectionOptionsDTO;

class SectionFactory
{
    public static function GetSectionOptions($value)
    {
        switch ($value->Type)
        {
            case 'Grid':
                return (new GridSectionOptionsDTO())->Merge($value);
            default:
                return (new StandardSectionOptionsDTO())->Merge($value);
        }
    }

    public static function GetSectionFromArray($value)
    {
        if($value==null)
            return [];

        $newList=[];
        foreach($value as $currentValue)
            $newList[]=SectionFactory::GetSectionOptions($currentValue);
        return $newList;
    }
}