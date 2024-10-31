<?php


namespace rnpagebuilder\PageBuilderOld\Renderers\Blocks\Core;


use rnpagebuilder\core\Utils\ArrayUtils;
use rnpagebuilder\DTO\RNBlockBaseOptionsDTO;
use rnpagebuilder\DTO\RunnableBlockBaseOptionsDTO;
use rnpagebuilder\PageBuilderOld\DataSources\Core\DataSourceBase;
use rnpagebuilder\PageBuilderOld\Renderers\ColumnRenderer;
use rnpagebuilder\PageBuilderOld\Renderers\Core\RendererBase;
use Twig\Markup;

abstract class BlockRendererBase extends RendererBase
{

    /** @var ColumnRenderer */
    public $Column;
    /** @var RNBlockBaseOptionsDTO */
    public $Options;
    /** @var RunnableBlockBaseOptionsDTO  */
    private $CachedRuntimeOptions=null;
    /** @var DataSourceBase */
    private $DataSource;
    public function __construct(ColumnRenderer $columnRenderer,RNBlockBaseOptionsDTO $options,$dataSource=null)
    {
        parent::__construct($columnRenderer->loader, $columnRenderer->twig);
        $this->Column=$columnRenderer;
        $this->Options=$options;
        $this->DataSource=$dataSource;
    }

    public function GetPageRenderer(){
        return $this->Column->GetPageRenderer();
    }

    public function GetBlockPostItem(){
        return $this->GetPageRenderer()->PageGenerator->GetFieldPostItem($this);
    }
    public function MaybeUpdateDataSource(){

    }

    public function InitializeWithDataSource()
    {

    }
    public function GetOptions(){
        if(!$this->HasRuntimeOptions())
            return null;
       if($this->CachedRuntimeOptions!=null)
           return $this->CachedRuntimeOptions;

       $this->CachedRuntimeOptions=$this->InternalGetOptions();
       if($this->CachedRuntimeOptions!=null)
       {
           $this->CachedRuntimeOptions->Id = $this->Options->Id;
           $this->CachedRuntimeOptions->Type=$this->Options->Type;

       }
       return $this->CachedRuntimeOptions;
    }

    protected function InternalGetOptions(){
        return null;

    }

    public function HasRuntimeOptions(){
        return false;
    }

    public function GetDefaultDataSource(){
        if($this->DataSource==null)
            return $this->GetPageRenderer()->GetDefaultDataSource();

        return $this->DataSource;
    }

    public function GetCurrentRow(){
        return $this->GetPageRenderer()->GetCurrentRow();
    }

    public function GetDataSourceById($dataSourceId)
    {
        return $this->GetPageRenderer()->GetDataSourceById($dataSourceId);
    }

    public function Render()
    {
        return new Markup($this->twig->render('Blocks/Core/BlockRendererBase.twig',['Model'=>$this]),'UTF-8');
    }


    public function SubRender(){
        if($this->GetTemplateName()=='')
            return '';
        return $this->RenderTemplate($this->GetTemplateName(),$this);
    }


}