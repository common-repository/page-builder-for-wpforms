<?php


namespace rnpagebuilder\PageBuilderOld;


use rnpagebuilder\core\Integration\IntegrationURL;
use rnpagebuilder\core\Loader;
use rnpagebuilder\core\TwigExtensions\Functions;
use rnpagebuilder\DTO\PageBuilderOptionsDTO;
use rnpagebuilder\DTO\RNPageOptionsDTO;
use rnpagebuilder\DTO\RunnableBlockBaseOptionsDTO;
use rnpagebuilder\PageBuilderOld\DataSources\Core\DataSourceBase;
use rnpagebuilder\PageBuilderOld\DataSources\Core\DataSourceInfo;
use rnpagebuilder\PageBuilderOld\DataSources\FormDataSource\FormDataSource;

use rnpagebuilder\PageBuilderOld\Renderers\Blocks\Core\BlockRendererBase;
use rnpagebuilder\PageBuilderOld\Renderers\PageRenderer;
use rnpagebuilder\Utilities\Sanitizer;
use Twig\Environment;

class PageBuilderGenerator
{
    /** @var PageBuilderOptionsDTO */
    public $Options;
    /** @var FormDataSource[] */
    public $DataSources;
    /** @var Loader */
    public $Loader;
    /** @var DataSourceInfo[] */
    public $DataSourceData;
    public $InternalID;
    public $PostItems;
    /** @var PostItem[] */
    public $GetItems;
    /** @var Environment */
    public $Twig;
    public $PageIndex=1;
    public $PageSize=1;
    /** @var PageRenderer */
    public $PageRenderer;
    /**
     * PageBuilderGenerator constructor.
     * @param $pageOptions PageBuilderOptionsDTO
     */
    public function __construct($loader, $pageOptions,$postItems=null)
    {

        if($postItems==null)
            $postItems=[];
        $this->GetItems=[];
        $this->PostItems=$postItems;
        $this->InternalID=uniqid();
        $this->Loader=$loader;
        $this->Options=$pageOptions;
        foreach ($pageOptions->Page->DataSources as $currentDataSource)
        {
            $formDataSource=new FormDataSource($this,$currentDataSource);
            $this->DataSources[]=$formDataSource;

            foreach($formDataSource->Options->Sort as $sortItem)
            {
                $formDataSource->AddSortItem($sortItem->FieldId,$sortItem->PathId,$sortItem->Orientation);
            }

            $this->PageSize=$currentDataSource->InitialNeededRows;

            $newPageSize=$this->GetPostItem('PageSize');
            if($newPageSize!=null)
                $this->PageSize=max(1,Sanitizer::SanitizeNumber($newPageSize,0));
        }


        require_once $this->Loader->DIR.'vendor/autoload.php';
        $loader = new \Twig\Loader\FilesystemLoader($this->Loader->DIR.'PageBuilder/Renderers');

        $this->Twig = new \Twig\Environment($loader, [
            'auto_reload'=>true,
            'debug'=>true,
            'strict_variables'=>true
        ]);

        $this->Twig->addExtension(new Functions($this->Loader));

        $this->PageRenderer=new PageRenderer($this->Loader,$this->Twig,$this);
        $this->PageRenderer->MaybeUpdateDataSource();
    }

    public function ClearSort(){
        if(count($this->DataSources)>0)
            $this->DataSources[0]->ClearSort();
    }

    public function SetPageSize($pageSize)
    {
        $this->PageSize=Max(1,intval($pageSize));
    }

    public function SetPageIndex($pageIndex)
    {
        $this->PageIndex=max(1,intval($pageIndex));
        if($this->PageIndex==0)
            $this->PageIndex=1;
    }

    public function IsPreview(){
        return $this->GetPostItem('IsPreview')!=null;
    }


    public function GetGetItem($name,$defaultValue=null)
    {
        foreach($this->GetItems as $value)
            if($value->Name==$name)
                return $value->Value;

        return $defaultValue;
    }

    public function GetPostItem($name,$defaultValue=null)
    {
        foreach($this->PostItems as $value)
            if($value->Name==$name)
                return $value->Value;

        return $defaultValue;
    }

    /**
     * @param $field BlockRendererBase
     */
    public function GetFieldPostItem($field)
    {
        foreach($this->PostItems as $currentPostItem)
        {
            if($currentPostItem->Name=='field_'.$field->Options->Id)
                return $currentPostItem->Value;
        }

        return null;
    }

    public function Execute()
    {
        $this->DataSourceData=$this->ExecuteInitialDataSource();
        return $this->Render();
       // $this->EnqueueFiles();
       // return $this->GetContainerHTML();
    }

    private function ExecuteInitialDataSource(){
        $dataSources=array();
        foreach($this->DataSources as $currentDataSource)
        {
            $dataSources[]=$currentDataSource->CreateRows($this->PageSize,($this->PageIndex-1)*$this->PageSize);

        }

        return $dataSources;
    }

    public function AddAction($action)
    {
        switch ($action->ActionName)
        {
            case 'GoToIndex':
                break;
        }
    }



    public function RetrieveGetItems()
    {
        foreach($_GET as $key=>$value)
        {
            $this->GetItems[]=new PostItem($key,$value);
        }
    }

    public function AddSort($field,  $path,$ori)
    {
        if(count($this->DataSources)>0)
            $this->DataSources[0]->AddSortItem($field,$path,$ori);
    }

    private function EnqueueFiles()
    {


        $this->Loader->AddScript('loader','js/lib/loader.js',array('wp-element'));
        $this->Loader->AddScript('core','js/dist/RNMainCore_bundle.js',array('@loader'));
        $this->Loader->AddScript('coreui','js/dist/RNMainCoreUI_bundle.js',array('@loader','@core'));
        $this->Loader->AddScript('page','js/dist/RNMainRNPage_bundle.js',array('@core'));

        $fieldDependencies=array();

        $styles=array('TextFieldBlock','NavigatorFieldBlock');
        foreach($this->Options->Dependencies as $dependency)
        {
            $fieldHook='rnpb'.$dependency;
            $this->Loader->AddScript($fieldHook,'js/dist/RNMain'.$dependency.'_bundle.js',array('@page'));

            if(array_search($dependency,$styles))
            {
                $this->Loader->AddStyle($fieldHook,'js/dist/RNMain'.$dependency.'_bundle.css');
            }

            $length = strlen('D' );
            if( !$length ) {
                return true;
            }
            if(substr( $dependency, -8 ) === 'Renderer')
            {
                $this->Loader->AddStyle($fieldHook,'js/dist/RNMain'.$dependency.'_bundle.css');
            }
            $fieldDependencies[]='@'.$fieldHook;
        }

        $forms=array();

        foreach($this->DataSources as $currentDataSource)
        {
            $forms=array_merge($currentDataSource->GetFormConfig(),$forms);
        }

        $this->Loader->AddScript('runnablepage','js/dist/RNMainRunnablePage_bundle.js',array_merge(array('@page'),$fieldDependencies));
        $this->Loader->AddStyle('core','js/dist/RNMainCoreUI_bundle.css');
        $this->Loader->AddStyle('rnpage','js/dist/RNMainRNPage_bundle.css');
        $this->Loader->LocalizeScript('rnPageRunnable','runnablepage','',array(
            'ajaxurl'=>IntegrationURL::AjaxURL()
        ));
        $this->Loader->LocalizeScript('rnPBVar_'.$this->InternalID,'runnablepage','RNPBNonce_'.$this->InternalID,array(
            "Id"=>$this->Options->Id,
            "PageOptions"=>$this->Options->Page,
            "DataSources"=>$this->DataSourceData,
            "FormList"=>$forms,
            'AdditionalOptions'=>array(
                'PostItems'=>$this->PostItems
            )
        ));


    }

    public function Maybe(){
        return 'eaea el maybe';
    }
    private function GetContainerHTML()
    {
        return '<div class="rnPBContainer" data-internal-id="'.$this->InternalID.'"></div>';
    }

    public function AddPostItem($name, $value)
    {
        $this->PostItems[]=new PostItem($name,$value);
    }

    public function AddGetItem($name, $value)
    {
        $this->GetItems[]=new PostItem($name,$value);
    }

    private function Render()
    {
        $this->PageRenderer->InitializeWithDataSource();
        return $this->PageRenderer->Render();
    }


}