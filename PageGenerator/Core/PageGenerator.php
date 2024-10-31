<?php

namespace rnpagebuilder\PageGenerator\Core;

use rnpagebuilder\core\Integration\IntegrationURL;
use rnpagebuilder\core\Loader;
use rnpagebuilder\core\Utils\ArrayUtils;
use rnpagebuilder\DTO\ConditionGroupOptionsDTO;
use rnpagebuilder\DTO\PageBuilderBaseOptionsDTO;
use rnpagebuilder\DTO\SortItemOptionsDTO;
use rnpagebuilder\PageGenerator\Blocks\Core\BlockBase;
use rnpagebuilder\PageGenerator\Core\Area\EditViewPageArea;
use rnpagebuilder\PageGenerator\Core\Area\PageAreaBase;
use rnpagebuilder\PageGenerator\Core\Area\SingleViewPageArea;
use rnpagebuilder\PageGenerator\Core\QueryBuilder\EntryRetriever\EntryRetriever;
use rnpagebuilder\PageGenerator\Core\QueryBuilder\Filters\FilterGroup;
use rnpagebuilder\PageGenerator\Core\QueryBuilder\QueryBuilder;
use rnpagebuilder\PageGenerator\Core\Section\PageSectionBase;
use rnpagebuilder\PageGenerator\Managers\ActionManager\ActionManager;
use rnpagebuilder\PageGenerator\Managers\MessageManager;
use rnpagebuilder\PageGenerator\Templates\ListingPageGenerator\ListingPageGenerator;
use rnpagebuilder\pr\PageGenerator\Area\PublicSingleViewPageArea;
use rnpagebuilder\pr\PageGenerator\Area\ToPostPageArea;
use rnpagebuilder\pr\PageGenerator\Templates\CalendarPageGenerator\CalendarPageGenerator;
use rnpagebuilder\PageGenerator\Templates\GridPageGenerator\GridPageGenerator;
use rnpagebuilder\pr\PageGenerator\Templates\CarouselPageGenerator\CarouselPageGenerator;
use rnpagebuilder\pr\PageGenerator\Templates\EntryToPostGenerator\EntryToPostGenerator;
use rnpagebuilder\pr\PageGenerator\Templates\SinglePageGenerator\SinglePageGenerator;
use rnpagebuilder\Utilities\Sanitizer;
use Twig\Markup;

abstract class PageGenerator extends RendererBase
{
    /** @var PageBuilderBaseOptionsDTO */
    public $Options;
    /** @var PageAreaBase */
    public $Area;
    /** @var Loader */
    public $Loader;
    /** @var PostItem[] */
    public $GetItems;
    /** @var EntryRetriever */
    public $EntryRetriever;
    /** @var PostItem[] */
    public $PostItems;
    public $InternalId;
    public $FieldOptions;
    /** @var PageSectionBase */
    public $RenderingSection;
    private $CurrentPage=0;
    private $GetParametersToUse;
    public $SkipInitialNonceValidation=false;
    private $defaultArea=null;
    public $Notifications;
    private $AllowedGlobalParameters=[];
    private $GlobalParametersToUse=null;
    private function __construct($loader,$options,$area=null,$postItems=null)
    {
        parent::__construct($loader);
        $this->Notifications=[];
        $this->FieldOptions=[];
        $this->InternalId=uniqid();
        if($postItems==null)
            $postItems=[];
        $this->PostItems=$postItems;
        $this->Loader=$loader;
        require_once $this->Loader->DIR.'vendor/autoload.php';
        $this->Options=$options;
        $this->EntryRetriever=new EntryRetriever($this,$this->GenerateQueryBuilder());
        $this->defaultArea=$area;
        $areaOptions=null;

    }

    public function AddGlobalParameters($parameterName){
        if(!isset($this->AllowedGlobalParameters[$parameterName]))
            $this->AllowedGlobalParameters[]=$parameterName;
    }

    public function Generate(){
        $result=$this->GenerateWithoutRender();
        if($result===true)
            return $this->Render();
        return $result;
    }

    public function MaybeExecuteAction(){
        if($this->GetGetParameter('rnaction')!='')
        {
            $action=ActionManager::GetAction($this);
            if($action==null)
            {
                $this->Notifications[]=MessageManager::ShowErrorMessage(__('Action not found, please try again','rnpagebuilder'));
                return;
            }

            if(!$action->IsValid())
            {
                $this->Notifications[]=MessageManager::ShowSuccessMessage(__('Sorry you can execute this action','rnpagebuilder'));
                return;
            }


            if(!$action->Execute())
                return;


            $urlToUse=$this->GetGetParameter('returnurl');
            if(!strpos($urlToUse,'?'))
                $urlToUse.='?';
            else
                $urlToUse.='&';

        /*   $urlToUse.='rnactionmessage='.urlencode($action->GetSuccessMessage());

            $text = '<script>';
            $text .='window.location="'.$this->GetGetParameter('returnurl').'"';
            $text .= '</script>';
            return $text;*/
        }
    }

    public function SetSkipInitialNonceValidation(){
        $this->SkipInitialNonceValidation=true;
    }

    public function GenerateWithoutRender(){
        $this->MaybeExecuteAction();
        $result=$this->Area->CanViewPage();
        if(is_string($result))
        {
            return $result;
        }
        $this->MaybeUpdateDataSource();
        $this->ExecuteQuery();
        return true;
    }

    public function Render()
    {
        $this->loader->AddScript('loader','js/lib/loader.js');
        $this->loader->AddScript('lit','js/dist/RNPBLit_bundle.js',array('@loader'));
        $this->loader->AddScript('core','js/dist/RNPBCore_bundle.js',array('@lit'));
        $this->loader->AddScript('runnablepage','js/dist/RNPBRunnablePage_bundle.js',array('@core'));
        $code= parent::Render();
        $this->AddVars();
        $this->Loader->AddScript('runnableExecuter','js/dist/RNPBRunnableExecuter_bundle.js',BlockBase::$DependencyHooks);
        return $code;
    }


    public function GetAreaSetting($settingName,$defaultValue='')
    {
        return Sanitizer::GetValueFromPath($this->Area->Options,$settingName);
    }

    public function GetMaxWidth(){
        $maxWidth=intval($this->Options->GeneralSettings->MaxWidth);
        if($maxWidth==0)
            return 'none';

        return $maxWidth.'px';

    }
    public function GetPageSize(){
        return $this->GetAreaSetting('MaximumNumberOfRecordsPerPage',1);
    }

    protected function GetRowSkip(){
        return $this->GetCurrentPage()*$this->GetPageSize();
    }

    public function MaybeUpdateDataSource()
    {
        $this->Area->MaybeUpdateDataSource();
    }



    /**
     * @param $loader
     * @param $options PageBuilderBaseOptionsDTO
     * @param $area
     */
    static function GetPageGenerator($loader,$options,$area=null)
    {
        if($options->Type=='Listing')
              return new ListingPageGenerator($loader,$options,$area);
        if($options->Type=='Grid')
            return new GridPageGenerator($loader,$options,$area);
        if($options->Type=='Calendar')
            return new CalendarPageGenerator($loader,$options,$area);
        if($options->Type=='Single')
            return new SinglePageGenerator($loader,$options,$area);
        if($options->Type=='EntryPost')
            return new EntryToPostGenerator($loader,$options,$area);
        if($options->Type='Carousel')
            return new CarouselPageGenerator($loader,$options,$area);
    }



    public function GetPostItem($name,$defaultValue=null)
    {
        foreach($this->PostItems as $value)
            if($value->Name==$name)
                return $value->Value;

        return $defaultValue;
    }

    /**
     * @return BlockBase[]
     */
    public function GetBlocks(){
        $blocks=[];
        foreach($this->Area->Sections as $currentSection)
            foreach ($currentSection->Rows as $currentRow)
                foreach($currentRow->Columns as $currentColumn)
                    foreach($currentColumn->Blocks as $currentBlock)
                    {
                        $blocks[]=$currentBlock;
                    }

        return $blocks;
    }

    public function GetBlocksByType($type){
        $blocks=[];
        foreach($this->GetBlocks() as $currentBlock)
            if($currentBlock->Options->Type==$type)
                $blocks[]=$currentBlock;

        return $blocks;
    }

    private function GenerateQueryBuilder()
    {
        $queryBuilder = new QueryBuilder($this->Loader, $this->Loader->GetRecordsTableName(), 'ROOT',[],$this->Options->FormId,$this);

        foreach ($this->Options->Filter->ConditionGroups as $conditionGroup)
        {
            $filterGroup = new FilterGroup($queryBuilder);
            $queryBuilder->Filters[]=$filterGroup;
            foreach ($conditionGroup->ConditionLines as $conditionLine)
            {
                $queryBuilder->GenerateQueryElements($filterGroup, $conditionLine);
            }


        }

        $queryBuilder->SortItems=$this->Options->Sort;
        $this->AddFormulas($queryBuilder);
        return $queryBuilder;
    }

    public function AddSort($fieldId,$pathId,$asc=true)
    {
        $sortItem=(new SortItemOptionsDTO())->Merge();
        $sortItem->FieldId=$fieldId;
        $sortItem->PathId=$pathId;
        $sortItem->Orientation=$asc?'asc':'desc';
        $this->EntryRetriever->QueryBuilder->SortItems=[$sortItem];

    }



    public function GetFieldPostItem($field)
    {
        foreach($this->PostItems as $currentPostItem)
        {
            if($currentPostItem->Name=='field_'.$field->Options->Id)
                return $currentPostItem->Value;
        }

        return null;
    }

    public function RenderArea(){
        return $this->Area->Render();
    }

    public function GetTemplateName()
    {
        return 'PageGenerator/Core/PageGenerator.twig';
    }


    public function IsEditingForm(){
        return $this->Area->Options->Id=='Edit';
    }

    protected function GenerateArea($areaOptions)
    {
        if($areaOptions->Id=='View')
            return new SingleViewPageArea($this,$areaOptions);
        if($areaOptions->Id=='Edit')
            return new EditViewPageArea($this,$areaOptions);
        if($areaOptions->Id=='SingleView')
            return new PublicSingleViewPageArea($this,$areaOptions);
        if($areaOptions->Id=='ToPost')
            return new ToPostPageArea($this,$areaOptions);


        return new PageAreaBase($this,$areaOptions);
    }

    /**
     * @param $conditionGroup ConditionGroupOptionsDTO
     * @return void
     */
    public function AddAdditionalFilters($conditionGroup)
    {
        $filterGroup=new FilterGroup($this->EntryRetriever->QueryBuilder,'and');
        $this->EntryRetriever->QueryBuilder->Filters[]=$filterGroup;
        foreach ($conditionGroup->ConditionLines as $conditionLine)
        {
            $this->EntryRetriever->QueryBuilder->GenerateQueryElements($filterGroup,$conditionLine);
        }
    }

    public function AddPostItem($name, $value)
    {
        $this->PostItems[]=new PostItem($name,$value);
    }

    /**
     * @param $blockOptions BlockBase
     * @param $additionalOptions
     * @return void
     */
    public function AddFieldOptions($blockOptions,$additionalOptions=[])
    {
        $index=$this->EntryRetriever->RowManager->CurrentRowIndex;
        $this->FieldOptions[]=array_merge([
            'Id'=>$blockOptions->Options->Id,
            'Type'=>$blockOptions->Options->Type,
            'Selector'=>$this->RenderingSection==null?'':$this->RenderingSection->GetFieldOptionsSelector()

        ],$additionalOptions);
    }

    public function GenerateOptions(){
        return [
            'ajaxurl'=>IntegrationURL::AjaxURL(),
        ];
    }
    private function AddVars()
    {
        $options=$this->GenerateOptions();
        if(count($this->FieldOptions)>0)
            $options['Fields']=$this->FieldOptions;
        $this->Loader->LocalizeScript('RNPage_'.$this->InternalId,'runnablepage','',$options);
    }

    public function GetGetParameter($paramName)
    {
        if(isset($this->GetParametersToUse[$paramName]))
            return sanitize_text_field($this->GetParametersToUse[$paramName]);
        return '';
    }


    public function GenerateLinkWithParameters($parameters)
    {
        $parameters['tid']=$this->Options->Id;
        $parameters=array_merge($this->GetGlobalParameters(),$parameters);
        return './?'.http_build_query($parameters);
    }

    public function GetGlobalParameters()
    {
        if($this->GlobalParametersToUse==null)
        {
            $this->GlobalParametersToUse=[];
            foreach($this->AllowedGlobalParameters as $currentGlobalParameter)
            {
                if(isset($this->GetParametersToUse[$currentGlobalParameter]))
                {
                    $this->GlobalParametersToUse[$currentGlobalParameter]=$this->GetParametersToUse[$currentGlobalParameter];
                }

            }

        }

        return $this->GlobalParametersToUse;
    }

    public function GenerateEntryLinkAction($actionName,$entryId=null,$returnUrl=null)
    {
        if($entryId===null)
            $entryId=$this->EntryRetriever->GetCurrentEntryId();


        return $this->GenerateLinkWithParameters(array_merge($this->GetParametersToUse,[
            'rnaction'=>$actionName,
            'ref'=>$entryId,
            'rnactionnonce'=>wp_create_nonce( $this->Options->Id.'_'.$actionName.'_'.$entryId),
        ]));
    }

    public function GetViewEntryURL($entryId=null){
        if($entryId==null)
            $entryId=$this->EntryRetriever->GetCurrentEntryId();
        return $this->GenerateLinkWithParameters([
            'ar'=>'view',
            'tp'=>$this->Options->Id,
            'form_id'=>$this->Options->FormId,
            'entryid'=>$entryId,
            'nonce'=>wp_create_nonce($this->Options->Id.'_'.$this->Options->FormId.'_'.$entryId.'_'.'view')
        ]);

    }

    public function GenerateLinkToSelf(){
        $tes= '?'.http_build_query(array_diff_key($this->GetParametersToUse,array_flip(['rnaction','ref','rnactionnonce','returnurl'])));
        return $tes;
    }

    public function GetDeleteEntryURL($entryId=null){
        return $this->GenerateEntryLinkAction('delete');
    }

    public function GetApproveEntryURL($entryId=null){
        return $this->GenerateEntryLinkAction('approve');

    }

    public function GetDisapproveEntryURL($entryId=null){
        return $this->GenerateEntryLinkAction('disapprove');

    }


    public function GetMainPageURL($entryId=null){
        if($entryId==null)
            $entryId=$this->EntryRetriever->GetCurrentEntryId();
        return $this->GenerateLinkWithParameters([
            'tp'=>$this->Options->Id,
            'form_id'=>$this->Options->FormId,
            'entryid'=>$entryId,
            'nonce'=>wp_create_nonce($this->Options->Id.'_'.$this->Options->FormId.'_'.$entryId.'_'.'view')
        ]);

    }

    public function InflateGetParameters($getParametersToUse=null){
        if($getParametersToUse==null)
            $this->GetParametersToUse=$_GET;
        else
            $this->GetParametersToUse=$getParametersToUse;

        $area=$this->defaultArea;
        if($area==null)
            $areaOptions=$this->GetAreaToUse();
        else
            $areaOptions=ArrayUtils::Find($this->Options->Areas,function ($item)use($area){
                return $area->Id==$area;
            });

        $this->Area=$this->GenerateArea($areaOptions);

        if(isset($this->GetParametersToUse['i'])&&is_numeric($this->GetParametersToUse['i']))
            $this->CurrentPage=intval($this->GetParametersToUse['i'])-1;


    }

    public function GetEditEntryURL($entryId=null)
    {
        if($entryId==null)
            $entryId=$this->EntryRetriever->GetCurrentEntryId();
        return $this->GenerateLinkWithParameters([
            'ar'=>'edit',
            'pbreturn'=>(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",
            'tp'=>$this->Options->Id,
            'form_id'=>$this->Options->FormId,
            'entryid'=>$entryId,
            'nonce'=>wp_create_nonce($this->Options->Id.'_'.$this->Options->FormId.'_'.$entryId.'_'.'edit')
        ]);

    }


    public function GetExportURL($entryId=null)
    {
        return $this->GenerateEntryLinkAction('export');

    }

    public function GetSortURL($fieldId,$pathId,$asc=true)
    {
        if($pathId!='')
            $fieldId.='_'.$pathId;
        return $this->GenerateLinkWithParameters([
            'sortby'=>$fieldId,
            'dir'=>$asc?'asc':'desc'
        ]);
    }


    public function GetPageLink($pageIndex){
        return $this->GenerateLinkWithParameters(['i'=>$pageIndex+1]);
    }

    public function GetTotalNumberOfPages (){
        if($this->GetPageSize()==0)
            return 0;
        return ceil($this->EntryRetriever->GetTotalRows()/$this->GetPageSize());
    }

    public function GetCurrentPage()
    {
        return $this->CurrentPage;
    }

    public function GetPageFirstRowIndex(){
        $expectedFirstRow= $this->GetCurrentPage()*$this->GetPageSize()+1;

        if($expectedFirstRow>$this->EntryRetriever->GetTotalRows())
            return 0;
        return $expectedFirstRow;
    }

    public function GetPageLastRowIndex(){
        $firstRowIndex=$this->GetPageFirstRowIndex();
        if($firstRowIndex==0)
            return 0;

        return min($firstRowIndex+$this->GetPageSize()-1,$this->EntryRetriever->GetTotalRows());

    }

    protected function GetAreaById($areaId)
    {
        foreach($this->Options->Areas as $currentArea)
            if($currentArea->Id==$areaId)
                return $this->GenerateArea($currentArea);
        return null;
    }

    private function GetAreaToUse()
    {
        if(!isset($this->GetParametersToUse['ar']))
            return $this->Options->Areas[0];

        $area=sanitize_text_field($this->GetParametersToUse['ar']);

        foreach($this->Options->Areas as $currentArea)
        {
            if(strtolower($currentArea->Id)==strtolower($area))
                return $currentArea;
        }

    }

    public function AddJSDependency($name,$dependencies=[])
    {
        $this->Loader->AddScript($name,'js/dist/RNPB'.$name.'_bundle.js',array_merge($dependencies,array('@runnablepage')));
        BlockBase::$DependencyHooks[]='@'.$name;
    }

    public function GetUsedSort()
    {
        if(count($this->EntryRetriever->QueryBuilder->SortItems)==0)
            return null;
        return $this->EntryRetriever->QueryBuilder->SortItems[0];
    }

    protected function ExecuteQuery()
    {
        $this->EntryRetriever->ExecuteQuery($this->GetPageSize(),$this->GetRowSkip());
    }

    /**
     * @param $queryBuilder QueryBuilder
     * @return void
     */
    private function AddFormulas($queryBuilder)
    {
        foreach ($this->Options->Formulas as $currentFormula)
            $queryBuilder->AddFormula($currentFormula);
    }

    public function MaybeShowNotifications(){
        $notifications='';
        foreach($this->Notifications as $currentNotification)
            $notifications.=$currentNotification;
        return new Markup($notifications,'UTF-8');
    }
}