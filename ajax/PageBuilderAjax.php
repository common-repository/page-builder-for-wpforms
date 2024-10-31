<?php


namespace rnpagebuilder\ajax;


use rnpagebuilder\core\db\core\DBManager;
use rnpagebuilder\core\db\PageRepository;
use rnpagebuilder\core\Exception\FriendlyException;
use rnpagebuilder\core\Managers\ExceptionManager\ExceptionManager;
use rnpagebuilder\core\Managers\UserManager;
use rnpagebuilder\core\Utils\ArrayUtils;
use rnpagebuilder\core\Utils\ObjectSanitizer;
use rnpagebuilder\DTO\core\Factories\PageBuilderFactory;
use rnpagebuilder\DTO\PageBuilderBaseOptionsDTO;
use rnpagebuilder\Managers\TemplateManager\TemplateManager;
use rnpagebuilder\PageGenerator\Core\PageGenerator;
use rnpagebuilder\pr\Repository\TemplateRepository;

class PageBuilderAjax extends AjaxBase
{

    function GetDefaultNonce()
    {
        return 'pagebuilder';
    }

    protected function RegisterHooks()
    {
        $this->RegisterPrivate('GetUserById','GetUserById');
        $this->RegisterPrivate('GetUsers','GetUsers');
        $this->RegisterPrivate('Save','Save');
        $this->RegisterPrivate('LoadForm','LoadForm','administrator',false);
        $this->RegisterPrivate('Preview','Preview','administrator');
        $this->RegisterPrivate('load_users_by_id','LoadUsersById','administrator');
        $this->RegisterPrivate('list_users','QueryUsers','administrator',true);
        $this->RegisterPrivate('save_template','SaveTemplate');
        $this->RegisterPrivate('remove_template','RemoveTemplate');
        $this->RegisterPrivate('get_template','GetTemplate');
        $this->RegisterPrivate('download_template','DownloadTemplate');
    }


    public function DownloadTemplate(){
        $id=sanitize_file_name($this->GetRequired('TemplateId'));

        $templateManager=new TemplateManager($this->Loader);
        $result=$templateManager->DownloadTemplate($id);

        if($result==false)
            $this->SendErrorMessage('Sorry, the template could not be retrieved, please try again');

        $this->SendSuccessMessage($result);
    }

    public function GetTemplate(){
        if(!$this->Loader->IsPR())
            $this->SendErrorMessage('Sorry, this feature is only available in the full version');

        try{
            $repository=new TemplateRepository($this->Loader);
            $result= $repository->GetTemplate($this->GetRequired('Id'));
            if($result==null)
                $this->SendErrorMessage('Template was not found');
            $this->SendSuccessMessage($result);
        }catch (\Exception $e)
        {
            $this->SendErrorMessage($e->getMessage());
        }
    }

    public function RemoveTemplate(){
        if(!$this->Loader->IsPR())
            $this->SendErrorMessage('Sorry, this feature is only available in the full version');

        try{
            $repository=new TemplateRepository($this->Loader);
            $result= $repository->DeleteTemplate($this->GetRequired('Id'));
            $this->SendSuccessMessage(["Id"=>$result]);
        }catch (\Exception $e)
        {
            $this->SendErrorMessage($e->getMessage());
        }
    }
    public function SaveTemplate(){
        if(!$this->Loader->IsPR())
            $this->SendErrorMessage('Sorry, this feature is only available in the full version');

        try{
            $repository=new TemplateRepository($this->Loader);
            $result= $repository->SaveTemplate($this->GetRequired('Type'),$this->GetRequired('Name'),$this->GetRequired('Kind'),$this->GetRequired('Options'));
            $this->SendSuccessMessage(["Id"=>$result]);
        }catch (\Exception $e)
        {
            $this->SendErrorMessage($e->getMessage());
        }
    }

    public function QueryUsers(){
        $query=$this->GetRequired('query');
        $wp_user_query = new \WP_User_Query(
            array(
                'search' => "*{$query}*",
                'search_columns' => array(
                    'user_login',
                    'user_nicename',
                    'user_email',
                ),

            ) );
        $users = $wp_user_query->get_results();

//search usermeta
        $wp_user_query2 = new \WP_User_Query(
            array(
                'meta_query' => array(
                    'relation' => 'OR',
                    array(
                        'key' => 'first_name',
                        'value' => $query,
                        'compare' => 'LIKE'
                    ),
                    array(
                        'key' => 'last_name',
                        'value' => $query,
                        'compare' => 'LIKE'
                    )
                )
            )
        );

        $users2 = $wp_user_query2->get_results();

        $totalusers_dup = array_merge($users,$users2);

        /** @var \WP_User[] $totalusers */
        $totalusers = array_unique($totalusers_dup, SORT_REGULAR);

        $info=[];
        foreach($totalusers as $currentUser)
        {
            $info[]=[
                'Label'=>$currentUser->user_firstname.' '.$currentUser->last_name.' ('.$currentUser->user_email.')',
                'Value'=>$currentUser->ID
            ];
        }

        $this->SendSuccessMessage($info);
    }


    public function LoadUsersById(){
        $ids=$this->GetRequired('Ids');

        $escapedIds=[];
        $users=[];
        global $wpdb;
        foreach($ids as $currentId)
        {
            $escapedIds[]=intval($currentId);
        }

        $dbmanager=new DBManager();

        if(count($escapedIds)>0)
        {
            $result=$dbmanager->GetResults("
                select ID,firstname.meta_value user_firstname,lastname.meta_value last_name,user_email user_email
                from ".$wpdb->users." usert
                left join ".$wpdb->usermeta." firstname on usert.ID = firstname.user_id and firstname.meta_key='first_name'
                left join ".$wpdb->usermeta." lastname on usert.ID = lastname.user_id and lastname.meta_key='last_name'
                where usert.ID in(" . implode($escapedIds) . ")
            ");

            foreach($result as $currentUser)
            {
                $users[]=[
                    'Label'=>$currentUser->user_firstname.' '.$currentUser->last_name.' ('.$currentUser->user_email.')',
                    'Value'=>$currentUser->ID
                ];
            }
        }
        $this->SendSuccessMessage($users);
    }

    public function LoadForm(){
        if(!isset($_GET['formid']))
            return '';
        $entryEditor=$this->Loader->ProcessorLoader->EntryEditor;
        echo $entryEditor->RenderForm(strval($_GET['formid']));
        wp_head();
        wp_footer();
        die();
    }

    public function Preview(){

        $userManager=new UserManager($this->Loader);
        if(!$userManager->UserCanViewPreview())
        {
            $exception=new ExceptionManager($this->Loader,new FriendlyException('Sorry you are not authorized to view previews'));
            echo $exception->PrintErrorToScreen();
            return;
        }




        try{
            if(!isset($_GET['tid']))
            {
                throw new FriendlyException('Template was not found, please try again');
            }

            $repository=new PageRepository($this->Loader);
            $pageBuilderOptions=$repository->GetPageById(intval($_GET['tid']));
            if($pageBuilderOptions==null)
                throw new FriendlyException('The template was not found');

            $pageGenerator=PageGenerator::GetPageGenerator($this->Loader,$pageBuilderOptions);
            $pageGenerator->InflateGetParameters();
            $pageGenerator->SetSkipInitialNonceValidation();
            return $pageGenerator->Generate();
        }catch (FriendlyException $e)
        {
            $exceptionManager=new ExceptionManager($this->Loader,$e);
            return $exceptionManager->PrintErrorToScreen();
        }


        if($pageBuilderOptions->FormId=='')
        {
            $exception=new ExceptionManager($this->Loader,new FriendlyException('This page does not have a form assigned to it, please go to the page designer and assign a form'));
            echo $exception->PrintErrorToScreen();
            return;
        }
/*
        $serverManager=new ServerActionsManager();
        try{
            $serverManager->MaybeRegisterFromURL($this->Loader);
        }catch (\Exception $e)
        {
            $exceptionManager=new ExceptionManager($this->Loader,$e);
            echo $exceptionManager->PrintErrorToScreen();
            return;
        }



        $postItems=null;
       if($options==null)
        {
            $postItems = $this->GetOptional('PostItems', null);
            if($postItems==null&&isset($_POST['PostItems']))
            {
                $postItemsToProcess = $_POST['PostItems'];
                $postItems=[];
                foreach($postItemsToProcess as $key=>$value)
                {
                    $postItems[]=[
                        'Name'=>$key,
                        'Value'=>stripslashes($value)
                    ];
                }
            }

            if ($postItems != null)
            {
                $postItems = Sanitizer::SanitizeArray($postItems);
                $postItems = ObjectSanitizer::Sanitize($postItems, array(array(
                    'Name' => '',
                    'Value' => new \stdClass()
                )));


                for($i=0;$i<count($postItems);$i++)
                {
                    $postItems[$i]=new PostItem($postItems[$i]->Name,$postItems[$i]->Value);
                }

                $isPreviewPostItem=ArrayUtils::Find($postItems,function ($item){return $item->Name=='IsPreview';});
                $previewOptions=ArrayUtils::Find($postItems,function ($item){return $item->Name=='PreviewOptions';});

                if($isPreviewPostItem->Value)
                    $options=$previewOptions->Value;
            }
        }

        $action=null;
        if(isset($_POST['action']))
        {
            $action=ObjectSanitizer::Sanitize(json_decode(stripslashes($_POST['action'])),[
                'ActionName'=>'',
                'Params'=>new \stdClass()
            ]);
        }

        $page=(new PageBuilderOptionsDTO())->Merge( $options);


        $generator=null;
        try{
            $generator=apply_filters('pagebuilder_before_loading_page',$generator,$page,$postItems);

        }catch (\Exception $e)
        {
            $exceptionManager=new ExceptionManager($this->Loader,$e);
            echo $exceptionManager->PrintErrorToScreen();
            return;
        }
        if($generator==null)
        {
            if($page==null||$options==null)
            {
                $exception=new ExceptionManager($this->Loader,new FriendlyException('Invalid preview, please try executing the preview again'));
                echo $exception->PrintErrorToScreen();
                return;
            }

            $generator = new PageBuilderGenerator($this->Loader, $page, $postItems);

            $generator->AddPostItem('PreviewOptions', $page);
        }

        $generator->AddPostItem('IsPreview', true);

        if(isset($_GET['index'])&&is_numeric($_GET['index']))
            $generator->SetPageIndex(intval($_GET['index']));


        if(isset($_GET['size'])&&is_numeric($_GET['size']))
            $generator->SetPageSize(intval($_GET['size']));


        if(isset($_GET['sort'])&&isset($_GET['ori'])&&isset($_GET['path']))
        {
            $generator->ClearSort();;
            $generator->AddSort($_GET['sort'],$_GET['path'],$_GET['ori']);
        }


        if($action!=null)
            $generator->AddAction($action);
        try
        {
            return $generator->Execute();
        }catch (\Exception $exception)
        {
            $exceptionManager=new ExceptionManager($this->Loader,$exception);
            echo $exceptionManager->PrintErrorToScreen();
        }*/

    }


    public function Save(){

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        /** @var PageBuilderOptionsDTO $options */
        $options=$this->GetRequired('Options');
        $pageOptions=PageBuilderFactory::GetPageOptions($options);
        $repository=new PageRepository($this->Loader);
        $pageId=null;
        try
        {
            $pageId=$repository->SavePage($pageOptions);
        }catch (\Exception $e)
        {
            $this->SendException($e);
        }

        $this->SendSuccessMessage(array('PageId'=>$pageId));


    }

    public function GetUserById(){
        $input=$this->GetRequired('Ids');


        if(!is_array($input)||count($input)==0)
            $this->SendSuccessMessage([]);

        $ids=[];
        global $wpdb;
        foreach($input as $currentId)
            $ids[]=$wpdb->prepare('%s',$currentId);


        $result=$wpdb->get_results("
            select user.ID,user_email Email,firstName.meta_value FirstName,lastName.meta_value LastName from ".$wpdb->users." user
            left join ".$wpdb->usermeta." firstName
            on user.ID=firstName.user_id and firstName.meta_key='first_name'
            left join ".$wpdb->usermeta." lastName
            on user.ID=lastName.user_id and lastName.meta_key='last_name'
            where user.ID in (".implode(',',$ids).")
            limit 100
        ");



        $this->SendSuccessMessage($result);
    }

    public function GetUsers(){
        $input=$this->GetRequired('input');
        global $wpdb;
        $result=$wpdb->get_results($wpdb->prepare("
            select user.ID,user_email Email,firstName.meta_value FirstName,lastName.meta_value LastName from ".$wpdb->users." user
            left join ".$wpdb->usermeta." firstName
            on user.ID=firstName.user_id and firstName.meta_key='first_name'
            left join ".$wpdb->usermeta." lastName
            on user.ID=lastName.user_id and lastName.meta_key='last_name'
            where user_email like '%".$wpdb->esc_like($input)."%' or concat(firstName.meta_value,' ',lastName.meta_value) like '%".$wpdb->esc_like($input)."%'
            limit 100
        ",$input,$input));



        $this->SendSuccessMessage($result);
    }
}