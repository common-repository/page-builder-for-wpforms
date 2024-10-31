<?php


namespace rnpagebuilder\PageBuilder\Renderers;


use rnpagebuilder\DTO\RNColumnOptionsDTO;
use rnpagebuilder\DTO\RunnableColumnOptionsDTO;
use rnpagebuilder\PageBuilder\Renderers\Blocks\Core\BlockRendererBase;
use rnpagebuilder\PageBuilder\Renderers\Blocks\Core\BlockRendererFactory;
use rnpagebuilder\PageBuilder\Renderers\Core\RendererBase;

class ColumnRenderer extends RendererBase
{
    /** @var RowRenderer */
    public $Row;
    /** @var BlockRendererBase */
    public $Block;
    /** @var RNColumnOptionsDTO */
    public  $Options;
    public function __construct(RowRenderer $row,RNColumnOptionsDTO $options,$dataSource)
    {
        parent::__construct($row->loader, $row->twig);
        $this->Row=$row;
        $this->Options=$options;
        $this->Block=BlockRendererFactory::GetRenderer($this,$options->Block,$dataSource);
    }

    public function GetPageRenderer()
    {
        return $this->Row->GetPageRenderer();
    }

    public function GetOptions(){
        $options=$this->Block->GetOptions();
        if($options==null)
            return null;

        $columnOptions=new RunnableColumnOptionsDTO();
        $columnOptions->Block=$options;
        return $columnOptions;
    }


    protected function GetTemplateName()
    {
        return 'ColumnRenderer.twig';
    }
}