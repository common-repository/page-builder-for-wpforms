<?php


namespace rnpagebuilder\PageBuilder\Renderers\Blocks\ListRenderer;


use rnpagebuilder\DTO\ImageBlockOptionsDTO;
use rnpagebuilder\DTO\ListBlockOptionsDTO;
use rnpagebuilder\DTO\RNBlockBaseOptionsDTO;
use rnpagebuilder\PageBuilder\Renderers\Blocks\Core\BlockRendererBase;
use rnpagebuilder\PageBuilder\Renderers\ColumnRenderer;
use rnpagebuilder\PageBuilder\Renderers\Core\RendererBase;
use rnpagebuilder\PageBuilder\Renderers\RowRenderer;

class ListRenderer extends BlockRendererBase
{
    /** @var RowRenderer[] */
    public $RowOptions;
    /** @var ListItemRenderer[] */
    public $ListItems;

    /** @var ListBlockOptionsDTO */
    public $Options;

    public function __construct(ColumnRenderer $columnRenderer, ListBlockOptionsDTO $options,$dataSource)
    {
        parent::__construct($columnRenderer, $options,$dataSource);

        $this->RowOptions=[];







    }

    public function InitializeWithDataSource()
    {
        $ds=$this->GetDefaultDataSource()->GetIterator();
        $this->ListItems=[];
        while ($ds->GetNextRow())
        {
            $this->ListItems[]=new ListItemRenderer($this->GetPageRenderer()->loader,$this->GetPageRenderer()->twig,$this,$ds);
        }
    }


    protected function GetTemplateName()
    {
        return 'Blocks/ListRenderer/ListRenderer.twig';
    }
}