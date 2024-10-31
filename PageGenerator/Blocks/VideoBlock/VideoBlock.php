<?php

namespace rnpagebuilder\PageGenerator\Blocks\VideoBlock;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;
use rnpagebuilder\core\Managers\SingleLineGenerator\SingleLineGenerator;
use rnpagebuilder\DTO\QRCodeBlockOptionsDTO;
use rnpagebuilder\DTO\VideoBlockOptionsDTO;
use rnpagebuilder\PageGenerator\Blocks\Core\BlockBase;

class VideoBlock extends BlockBase
{
    /** @var VideoBlockOptionsDTO */
    public $Options;
    public $Text;
    public $URL;
    public $ImageSrc;
    protected function GetTemplateName()
    {
        return 'PageGenerator/Blocks/VideoBlock/VideoBlock.twig';
    }

    public function GetURL(){
        if($this->Options->MediaData==null||$this->Options->MediaData->URL=='')
            return '';

        return $this->Options->MediaData->URL;
    }

    public function GetAttributes(){
        $attributes=[];
        if($this->Options->AutoPlay)
            $attributes[]='autoplay';

        if($this->Options->Loop)
            $attributes[]='loop';

        if($this->Options->ShowControls)
            $attributes[]='controls';

        if($this->Options->Muted)
            $attributes[]='muted';
        return implode(' ',$attributes);
    }


}