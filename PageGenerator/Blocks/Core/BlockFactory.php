<?php

namespace rnpagebuilder\PageGenerator\Blocks\Core;

use rnpagebuilder\core\Exception\FriendlyException;
use rnpagebuilder\DTO\BlockBaseOptionsDTO;
use rnpagebuilder\PageGenerator\Blocks\FieldBlock\FieldBlock;
use rnpagebuilder\PageGenerator\Blocks\FieldImageBlock\FieldImageBlock;
use rnpagebuilder\PageGenerator\Blocks\FieldSummary\FieldSummary;
use rnpagebuilder\PageGenerator\Blocks\ImageBlock\ImageBlock;
use rnpagebuilder\PageGenerator\Blocks\InnerSectionBlock;
use rnpagebuilder\PageGenerator\Blocks\LinkBlock\LinkBlock;
use rnpagebuilder\PageGenerator\Blocks\NavigationBlock\NavigationBlock;
use rnpagebuilder\PageGenerator\Blocks\QRCodeBlock\QRCodeBlock;
use rnpagebuilder\PageGenerator\Blocks\TextBlock;
use rnpagebuilder\PageGenerator\Blocks\VideoBlock\VideoBlock;
use rnpagebuilder\pr\PageGenerator\Blocks\ChartBlock\ChartBlock;
use rnpagebuilder\pr\PageGenerator\Blocks\HTMLBlock\HTMLBlock;
use rnpagebuilder\pr\PageGenerator\Blocks\PageInformation\PageInformationBlock;
use rnpagebuilder\pr\PageGenerator\Blocks\PoUpBlock\PopUpBlock;
use rnpagebuilder\pr\PageGenerator\Blocks\SearchBar\SearchBarBlock;


class BlockFactory
{
    /**
     * @param $blockBaseOptions BlockBaseOptionsDTO
     */
    public static function GetBlock($parent,$blockBaseOptions){
        switch ($blockBaseOptions->Type)
        {
            case 'Text':
            case 'TextWithFields':
                return new TextBlock($parent,$blockBaseOptions);
            case 'SearchBar':
                return new SearchBarBlock($parent,$blockBaseOptions);
            case 'Field':
                return new FieldBlock($parent,$blockBaseOptions);
            case 'InnerSection':
                return new InnerSectionBlock($parent,$blockBaseOptions);
            case 'FieldImage':
                return new FieldImageBlock($parent,$blockBaseOptions);
            case 'Navigation':
                return new NavigationBlock($parent,$blockBaseOptions);
            case 'PageInformation':
                return new PageInformationBlock($parent,$blockBaseOptions);
            case 'FieldSummary':
                return new FieldSummary($parent,$blockBaseOptions);
            case 'Link':
                return new LinkBlock($parent,$blockBaseOptions);
            case 'QRCode':
                return new QRCodeBlock($parent,$blockBaseOptions);
            case 'Image':
                return new ImageBlock($parent,$blockBaseOptions);
            case 'Video':
                return new VideoBlock($parent,$blockBaseOptions);
            case 'HTML':
                return new HTMLBlock($parent,$blockBaseOptions);
            case 'Chart':
                return new ChartBlock($parent,$blockBaseOptions);
            case 'Popup':
                return new PopUpBlock($parent,$blockBaseOptions);
            default:
                throw new FriendlyException('Unknown block type '.$blockBaseOptions->Type);
        }
    }
}