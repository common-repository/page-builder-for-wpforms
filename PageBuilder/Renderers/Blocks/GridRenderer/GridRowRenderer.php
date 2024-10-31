<?php


namespace rnpagebuilder\PageBuilder\Renderers\Blocks\GridRenderer;


use rnpagebuilder\DTO\FormFieldBlockOptionsDTO;
use rnpagebuilder\DTO\GridFieldBlockOptionsDTO;
use rnpagebuilder\DTO\RNBlockBaseOptionsDTO;
use rnpagebuilder\DTO\TextFieldBlockOptionsDTO;
use rnpagebuilder\PageBuilder\DataSources\Core\DataSourceRow;
use rnpagebuilder\PageBuilder\Renderers\Blocks\Core\BlockRendererBase;
use rnpagebuilder\PageBuilder\Renderers\ColumnRenderer;
use rnpagebuilder\PageBuilder\Renderers\Core\RendererBase;
use rnpagebuilder\SlateGenerator\SlateTextGenerator\SlateTextGenerator;
use Twig\Markup;

class GridRowRenderer extends RendererBase
{
    /** @var GridCellRenderer */
    public $Cells;
    /** @var TextFieldBlockOptionsDTO */
    public $Options;
    /** @var GridRenderer */
    public $Grid;
    /** @var DataSourceRow */
    public $RowData;
    public function __construct(GridRenderer $grid,$rowData)
    {
        parent::__construct($grid->loader,$grid->twig);
        $this->Grid=$grid;
        $this->RowData=$rowData;

        foreach ($grid->Options->Columns as $currentColumn)
        {
            $this->Cells[]=new GridCellRenderer($this,$currentColumn);
        }
    }


    protected function GetTemplateName()
    {
        return 'Blocks/GridRenderer/GridRowRenderer.twig';
    }

    public function GetLabel(){
        return '';
    }

    public function GetValue(){
        return '';
    }


}

