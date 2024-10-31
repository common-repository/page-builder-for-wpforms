<?php


namespace rnpagebuilder\PageBuilderOld\Renderers\Blocks\ListRenderer;


use rnpagebuilder\PageBuilderOld\DataSources\Core\DataSourceBase;
use rnpagebuilder\PageBuilderOld\Renderers\Core\RendererBase;
use rnpagebuilder\PageBuilderOld\Renderers\RowRenderer;

class ListItemRenderer extends RendererBase
{
    /**
     * ListItemRenderer constructor.
     * @param $loader
     * @param $twig
     * @param $ds DataSourceBase
     */

    /** @var DataSourceBase */
    public $DataSource;

    /** @var RowRenderer[] */
    public $Rows;

    /**
     * ListItemRenderer constructor.
     * @param $loader
     * @param $twig
     * @param $listRenderer ListRenderer
     * @param $ds DataSourceBase
     */
    public function __construct($loader, $twig,$listRenderer,$ds)
    {
        parent::__construct($loader, $twig);
        $this->AddStyle('ListItem','PageBuilder/Renderers/Blocks/ListRenderer/ListItemRenderer.css');

        $ds=$ds->GetIterator(true);
        foreach($listRenderer->Options->Rows as $currentRow)
        {
            $this->Rows[]=new RowRenderer($listRenderer->GetPageRenderer(),$currentRow,$ds);
        }

    }


    protected function GetTemplateName()
    {
        return 'Blocks/ListRenderer/ListItemRenderer.twig';
    }
}