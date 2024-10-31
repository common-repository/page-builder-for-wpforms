<?php

namespace rnpagebuilder\PageGenerator\Blocks\QRCodeBlock;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;
use rnpagebuilder\core\Managers\SingleLineGenerator\SingleLineGenerator;
use rnpagebuilder\DTO\QRCodeBlockOptionsDTO;
use rnpagebuilder\PageGenerator\Blocks\Core\BlockBase;

class QRCodeBlock extends BlockBase
{
    /** @var QRCodeBlockOptionsDTO */
    public $Options;
    public $Text;
    public $URL;
    public $ImageSrc;
    protected function GetTemplateName()
    {
        return 'PageGenerator/Blocks/QRCodeBlock/QRCodeBlock.twig';
    }

    protected function BeforeRender()
    {
        parent::BeforeRender();
        $pageGenerator=$this->GetPageGenerator();
        $generator=new SingleLineGenerator($pageGenerator);

        $this->Text=$generator->GetText($this->Options->Content);
        $this->ImageSrc= $result=Builder::create()
            ->writer(new PngWriter())
            ->encoding(new Encoding('UTF-8'))
            ->size(128)
            ->data($this->Text)
            ->build()->getDataUri();

    }



}