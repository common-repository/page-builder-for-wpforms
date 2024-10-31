<?php


namespace rnpagebuilder\PageBuilderOld\Renderers\Blocks\GridRenderer;


use rnpagebuilder\DTO\FormFieldBlockOptionsDTO;
use rnpagebuilder\DTO\GridFieldBlockOptionsDTO;
use rnpagebuilder\DTO\RNBlockBaseOptionsDTO;
use rnpagebuilder\DTO\TextFieldBlockOptionsDTO;
use rnpagebuilder\PageBuilderOld\DataSources\Core\DataSourceRow;
use rnpagebuilder\PageBuilderOld\Renderers\Blocks\Core\BlockRendererBase;
use rnpagebuilder\PageBuilderOld\Renderers\ColumnRenderer;
use rnpagebuilder\PageBuilderOld\Renderers\Core\RendererBase;
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

