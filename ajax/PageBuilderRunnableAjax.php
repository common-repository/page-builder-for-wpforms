<?php


namespace rnpagebuilder\ajax;


use rnpagebuilder\core\db\core\RepositoryBase;
use rnpagebuilder\core\db\PageRepository;
use rnpagebuilder\core\Exception\FriendlyException;
use rnpagebuilder\core\Managers\ExceptionManager\ExceptionManager;
use rnpagebuilder\core\Managers\UserManager;
use rnpagebuilder\core\Utils\ArrayUtils;
use rnpagebuilder\core\Utils\ObjectSanitizer;
use rnpagebuilder\DTO\PageBuilderOptionsDTO;
use rnpagebuilder\DTO\RNPageOptionsDTO;
use rnpagebuilder\PageBuilderOld\PageBuilderGenerator;
use rnpagebuilder\Utilities\Sanitizer;

class PageBuilderRunnableAjax extends AjaxBase
{

    function GetDefaultNonce()
    {
        return 'pagebuilder';
    }

    protected function RegisterHooks()
    {
        $this->RegisterPublic('GetNextRows','GetNextRows',false);

    }

    public function GetNextRows(){
        $pageId=$this->GetRequired('PageId');
        $dataSource=$this->GetRequired('DataSource');
        $nonce=$this->GetRequired('Nonce');
        $index=$this->GetRequired('Index');
        $count=$this->GetRequired('Count');
        $postItems=$this->GetRequired('PostItems');

        if(!wp_verify_nonce($nonce,'ds_next_'.$pageId.'_'.$dataSource))
            $this->SendErrorMessage('Invalid request');

        $postItems=Sanitizer::SanitizeArray($postItems);
        $postItems=ObjectSanitizer::Sanitize($postItems,array(array(
            'Name'=>'',
            'Value'=>null
        )));

        $isPreviewPostItem=ArrayUtils::Find($postItems,function ($item){return $item->Name=='IsPreview';});
        $previewOptions=ArrayUtils::Find($postItems,function ($item){return $item->Name=='PreviewOptions';});

        if($isPreviewPostItem!=null&&$isPreviewPostItem->Value==true&&$previewOptions!=null&&$previewOptions->Value!=null
            &&(new UserManager($this->Loader))->UserCanViewPreview())
            $options=$previewOptions->Value;
        else
        {
            $repository=new PageRepository($this->Loader);
            $options=$repository->GetPageById($pageId);
        }


        if($options==null)
            $this->SendErrorMessage('Invalid page');


        $pageGenerator=new PageBuilderGenerator($this->Loader,$options);
        foreach($pageGenerator->DataSources as $currentDatasource)
        {
            if($currentDatasource->Options->Id==$dataSource)
            {
                $rows=$currentDatasource->GetRows($count,$index);
                $this->SendSuccessMessage($rows);
            }
        }

        $this->SendErrorMessage('Data source not found');

    }
}