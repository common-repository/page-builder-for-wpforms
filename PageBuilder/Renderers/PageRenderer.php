<?php


namespace rnpagebuilder\PageBuilder\Renderers;


use rnpagebuilder\core\Integration\IntegrationURL;
use rnpagebuilder\DTO\RunnablePageOptionsDTO;
use rnpagebuilder\PageBuilder\DataSources\FormDataSource\FormDataSource;
use rnpagebuilder\PageBuilder\PageBuilderGenerator;
use rnpagebuilder\PageBuilder\Renderers\Core\RendererBase;
use Twig\Environment;

class PageRenderer extends RendererBase
{
    /** @var PageBuilderGenerator  */
    public $PageGenerator;
    /** @var RowRenderer[] */
    public $Rows;
    private static $PageOptions=[];
    public $Id;
    /**
     * PageRenderer constructor.
     * @param $twig Environment
     * @param $pageGenerator PageBuilderGenerator
     */
    public function __construct($loader,$twig,$pageGenerator)
    {
        parent::__construct($loader,$twig);
        $this->Id=uniqid();
        $this->PageGenerator=$pageGenerator;
        $this->Rows=[];

        foreach ($pageGenerator->Options->Page->Rows as $currentRow)
        {
            $this->Rows[]=new RowRenderer($this,$currentRow);
        }

    }

    public function InitializeWithDataSource(){
        foreach($this->Rows as $currentRow)
            foreach($currentRow->Columns as $currentColumn)
                $currentColumn->Block->InitializeWithDataSource();
    }

    /**
     * @return RunnablePageOptionsDTO
     */
    public function GetOptions(){
        $rowOptions=[];
        foreach($this->Rows as $currentRow)
        {
            $currentRowOptions=$currentRow->GetOptions();
            if($currentRowOptions!=null)
                $rowOptions[]=$currentRowOptions;
        }

        if(count($rowOptions)==0)
            return null;

        $pageOptions=(new RunnablePageOptionsDTO())->Merge();
        $pageOptions->Rows=$rowOptions;
        $pageOptions->Id=$this->Id;

        return $pageOptions;

    }
    public function GetFieldById($fieldId)
    {
        /** @var FormDataSource $ds */
        $ds=$this->GetDefaultDataSource();
        if($ds==null)
            return null;

        return $ds->GetFieldById($fieldId);
    }
    public function GetTotalNumberOfRows(){
        $ds=$this->GetDefaultDataSource();
        if($ds==null)
            return 0;

        return $ds->Count;
    }

    public function GetPageSize(){
        return $this->PageGenerator->PageSize;
    }

    public function GetDataSourceById($dataSourceId)
    {
        foreach($this->PageGenerator->DataSources as $currentDataSource)
        {
            if ($currentDataSource->Options->Id == $dataSourceId)
                return $currentDataSource;
        }

        return null;
    }

    public function GetDefaultDataSource(){
        if(count($this->PageGenerator->DataSources)>0)
            return $this->PageGenerator->DataSources[0];

        return null;
    }

    public function GetCurrentRow()
    {
        $ds=$this->GetDefaultDataSource();
        if($ds==null)
            return null;

        return $ds->GetCurrentRow();
    }

    public function MaybeUpdateDataSource()
    {
        foreach($this->Rows as $currentRow)
            foreach($currentRow->Columns as $currentColumn)
                $currentColumn->Block->MaybeUpdateDataSource();
    }

    protected function GetTemplateName()
    {
        return 'PageRenderer.twig';
    }



    public function RenderTemplate($templateName, $model)
    {
        $options=$this->GetOptions();


        if($options!=null)
        {
            self::$PageOptions[] = $options;
        }

        $this->AddStyle('pagerenderer','PageBuilder/Renderers/PageRenderer.css');
        $html= parent::RenderTemplate($templateName, $model); // TODO: Change the autogenerated stub

        $this->loader->LocalizeScript('rnPageSharedVar','runnablepage','',array(
            'ajaxurl'=>IntegrationURL::AjaxURL(),
            '_prefix'=>'rnpagebuilder'
        ));

        $this->loader->LocalizeScript('rnPageVar','runnablepage','',['Options'=>self::$PageOptions]);

        $this->loader->AddScript('RNMainRunnablePageInitializer_bundle','js/dist/RNMainRunnablePageInitializer_bundle.js',  RendererBase::$DependencyHooks);

        return $html;
    }


}