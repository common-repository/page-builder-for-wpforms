<?php


namespace rnpagebuilder\DTO\core\Factories;


use rnpagebuilder\core\Exception\FriendlyException;
use rnpagebuilder\DTO\BlockBaseOptionsDTO;
use rnpagebuilder\DTO\ChartBlockOptionsDTO;
use rnpagebuilder\DTO\FieldBlockOptionsDTO;
use rnpagebuilder\DTO\FieldImageBlockOptionsDTO;
use rnpagebuilder\DTO\HTMLBlockOptionsDTO;
use rnpagebuilder\DTO\ImageBlockOptionsDTO;
use rnpagebuilder\DTO\InnerSectionBlockOptionsDTO;
use rnpagebuilder\DTO\LinkBlockOptionsDTO;
use rnpagebuilder\DTO\NavigationBlockOptionsDTO;
use rnpagebuilder\DTO\PageInformationBlockOptionsDTO;
use rnpagebuilder\DTO\PopUpBlockOptionsDTO;
use rnpagebuilder\DTO\QRCodeBlockOptionsDTO;
use rnpagebuilder\DTO\SearchBarBlockOptionsDTO;
use rnpagebuilder\DTO\SummaryBlockOptionsDTO;
use rnpagebuilder\DTO\TextBlockOptionsDTO;
use rnpagebuilder\DTO\TextWithFieldsBlockOptionsDTO;
use rnpagebuilder\DTO\VideoBlockOptionsDTO;

class BlockFactory
{

    /**
     * @param $param BlockBaseOptionsDTO
     * @throws FriendlyException
     */
    public static function GetBlockOptions($param)
    {
        /** @var BlockBaseOptionsDTO $blockOptions */
        $blockOptions=null;

        if($param==null)
            return null;
        if(is_array($param))
        {
            $options=[];
            foreach($param as $item)
                $options[]=BlockFactory::GetBlockOptions($item);
            return $options;
        }

        switch ($param->Type)
        {
            case 'Text':
                $blockOptions= new TextBlockOptionsDTO();
                break;
            case 'TextWithFields':
                $blockOptions=new TextWithFieldsBlockOptionsDTO();
                break;
            case 'SearchBar':
                $blockOptions=new SearchBarBlockOptionsDTO();
                break;
            case 'Field':
                $blockOptions=new FieldBlockOptionsDTO();
                break;
            case 'InnerSection':
                $blockOptions=new InnerSectionBlockOptionsDTO();
                break;
            case 'FieldImage':
                $blockOptions=new FieldImageBlockOptionsDTO();
                break;
            case 'Navigation':
                $blockOptions=new NavigationBlockOptionsDTO();
                break;
            case 'PageInformation':
                $blockOptions=new PageInformationBlockOptionsDTO();
                break;
            case 'FieldSummary':
                $blockOptions=new SummaryBlockOptionsDTO();
                break;
            case 'Link':
                $blockOptions=new LinkBlockOptionsDTO();
                break;
            case 'QRCode':
                $blockOptions=new QRCodeBlockOptionsDTO();
                break;
            case 'Image':
                $blockOptions=new ImageBlockOptionsDTO();
                break;
            case 'Video':
                $blockOptions=new VideoBlockOptionsDTO();
                break;
            case 'HTML':
                $blockOptions=new HTMLBlockOptionsDTO();
                break;
            case 'Chart':
                $blockOptions=new ChartBlockOptionsDTO();
                break;
            case 'Popup':
                $blockOptions=new PopUpBlockOptionsDTO();
                break;
            default:
                throw new FriendlyException('Invalid block type '.$param->Type);

        }

        $blockOptions->Merge($param);
        return $blockOptions;

    }
}