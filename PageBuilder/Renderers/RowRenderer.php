<?php


namespace rnpagebuilder\PageBuilder\Renderers;


use rnpagebuilder\DTO\RNRowOptionsDTO;
use rnpagebuilder\DTO\RunnableRowOptionsDTO;
use rnpagebuilder\PageBuilder\Renderers\Core\RendererBase;

class RowRenderer extends RendererBase
{
    /** @var PageRenderer */
    public $PageRenderer;
    /** @var ColumnRenderer[] */
    public $Columns;
    /** @var RNRowOptionsDTO */
    public $Options;
    /**
     * RowRenderer constructor.
     * @param $loader
     * @param $twig
     * @param $rowOptions RNRowOptionsDTO
     */
    public function __construct(PageRenderer $pageRenderer,$rowOptions,$dataSource=null)
    {
        parent::__construct($pageRenderer->loader, $pageRenderer->twig);
        $this->PageRenderer=$pageRenderer;
        $this->Options=$rowOptions;
        $this->Columns=[];
        foreach($this->Options->Columns as $currentColumn)
        {
            $this->Columns[]=new ColumnRenderer($this,$currentColumn,$dataSource);
        }
    }

    public function GetPageRenderer()
    {
        return $this->PageRenderer;
    }

    public function GetOptions(){
        $columnOptions=[];
        foreach($this->Columns as $currentColumn)
        {
            $options=$currentColumn->GetOptions();
            if($options==null)
                continue;
            $columnOptions[]=$options;
        }

        if(count($columnOptions)==0)
            return null;

        $options= (new RunnableRowOptionsDTO())->Merge();
        $options->Columns=$columnOptions;
        return $options;

    }


    protected function GetTemplateName()
    {
        return 'RowRenderer.twig';
    }
}