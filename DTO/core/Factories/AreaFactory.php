<?php

namespace rnpagebuilder\DTO\core\Factories;

use rnpagebuilder\DTO\CarouselPageAreaOptionsDTO;
use rnpagebuilder\DTO\EditPageAreaOptionsDTO;
use rnpagebuilder\DTO\MultiplePageAreaOptionsDTO;
use rnpagebuilder\DTO\PageAreaBaseOptionsDTO;
use rnpagebuilder\DTO\SingleViewAreaOptionsDTO;

class AreaFactory
{
    public static function GetOption($option)
    {
        if($option==null)
            return null;
        switch($option->Id)
        {
            case 'Multiple':
                $dto= new MultiplePageAreaOptionsDTO();
                break;
            case 'Edit':
                $dto= new EditPageAreaOptionsDTO();
                break;
            case 'SingleView':
                $dto=new SingleViewAreaOptionsDTO();
                break;
            case 'Carousel':
                $dto=new CarouselPageAreaOptionsDTO();
                break;
            default:
                $dto=new PageAreaBaseOptionsDTO();
                break;
        }

        $dto->Merge($option);
        return $dto;
    }


    public static function GetOptionsFromList($value)
    {
        if(!is_array($value))
            $value=[$value];

        $arrayToUse=[];
        foreach($value as $currentvalue)
        {
            $arrayToUse[]=self::GetOption($currentvalue);
        }

        return $arrayToUse;

    }
}