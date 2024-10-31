<?php

namespace rnpagebuilder\DTO\core\Factories;

use rnpagebuilder\DTO\CalendarPageOptionsDTO;
use rnpagebuilder\DTO\CarouselPageOptionsDTO;
use rnpagebuilder\DTO\EntryToPostOptionsDTO;
use rnpagebuilder\DTO\GridPageOptionsDTO;
use rnpagebuilder\DTO\ListingPageOptionsDTO;
use rnpagebuilder\DTO\PageBuilderBaseOptionsDTO;
use rnpagebuilder\DTO\SinglePageOptionsDTO;

class PageBuilderFactory
{
    /**
     * @param $options PageBuilderBaseOptionsDTO
     * @return PageBuilderBaseOptionsDTO
     */
    public static function GetPageOptions($options)
    {
        switch ($options->Type)
        {
            case 'Listing':
                return (new ListingPageOptionsDTO())->Merge($options);
            case 'Grid':
                return (new GridPageOptionsDTO())->Merge($options);
            case 'Calendar':
                return (new CalendarPageOptionsDTO())->Merge($options);
            case 'Single':
                return (new SinglePageOptionsDTO())->Merge($options);
            case 'EntryPost':
                return (new EntryToPostOptionsDTO())->Merge($options);
            case 'Carousel':
                return (new CarouselPageOptionsDTO())->Merge($options);
            default:
                throw new \Exception('Invalid page builder type '.$options->Type);
        }
    }
}