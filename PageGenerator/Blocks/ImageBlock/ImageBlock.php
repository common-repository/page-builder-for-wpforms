<?php

namespace rnpagebuilder\PageGenerator\Blocks\ImageBlock;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;
use rnpagebuilder\core\Managers\SingleLineGenerator\SingleLineGenerator;
use rnpagebuilder\DTO\ImageBlockOptionsDTO;
use rnpagebuilder\DTO\QRCodeBlockOptionsDTO;
use rnpagebuilder\PageGenerator\Blocks\Core\BlockBase;

class ImageBlock extends BlockBase
{
    /** @var ImageBlockOptionsDTO */
    public $Options;
    protected function GetTemplateName()
    {
        return 'PageGenerator/Blocks/ImageBlock/ImageBlock.twig';
    }


    public function GetURL(){
        if($this->Options->MediaData==null||$this->Options->MediaData->URL=='')
            return '';

        return $this->Options->MediaData->URL;
    }



}