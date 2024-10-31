<?php


namespace rnpagebuilder\Pages;


use rnpagebuilder\core\db\core\DBManager;
use rnpagebuilder\core\db\PageRepository;
use rnpagebuilder\core\Integration\IntegrationURL;
use rnpagebuilder\core\LibraryManager;
use rnpagebuilder\core\Managers\PreviewManager\PreviewManager;
use rnpagebuilder\core\PageBase;
use rnpagebuilder\pr\Repository\TemplateRepository;

class PageBuilder extends PageBase
{

    public function Render()
    {

        $libraryManager=new LibraryManager($this->Loader);
        $libraryManager->AddCalendar();
        $libraryManager->AddCore();
        $libraryManager->AddCoreUI();
        $libraryManager->AddTextEditor();
        $libraryManager->AddAlertDialog();
        $libraryManager->AddTooltip();
        $libraryManager->AddPageBuilder();
        $libraryManager->AddChart();
        $libraryManager->AddTooltip();
        $this->Loader->AddScript('runnablepageBuilder','js/dist/RNPBRunnablePageBuilder_bundle.js',$libraryManager->GetDependencyHooks());
        wp_enqueue_media();
        $this->Loader->ProcessorLoader->FormProcessor->SyncCurrentForms();

        $templateData=null;
        if(isset($_GET['templateId']))
        {
            $templateId=intval($_GET['templateId']);
            if($templateId>0)
            {
                $repository = new PageRepository($this->Loader);
                $options = $repository->GetPageById($templateId);
            }else{
                $type= sanitize_file_name($_GET['type']);
                $subtype=sanitize_file_name($_GET['subtype']);
                if(array_search($type,['listing','grid','calendar','single','entrypost','carousel'])===false)
                {
                    echo 'Invalid template, please pick a template again';
                    return;
                }

                $subtype=sanitize_file_name($_GET['subtype']);
                if(!file_exists($this->Loader->DIR.'templates/'.$type.'/'.$subtype.'.json'))
                {
                    echo 'Template was not found, please pick a different template';
                    return;
                }

                $options=json_decode(file_get_contents($this->Loader->DIR.'templates/'.$type.'/'.$subtype.'.json'));
                if($options==null)
                {
                    echo 'Invalid template, please pick a template again';
                    return;
                }




            }
        }

        global $wp_roles;
        $all_roles = $wp_roles->roles;

        $roles=[];
        foreach($all_roles as $key=>$value)
        {
            $roles[]=array('Label'=>$value['name'],'Id'=>$key);
        }


        $pdfBuilderTemplates=[];
        if(function_exists('RNPDFBuilder'))
        {
            $pdfBuilderTemplates=RNPDFBuilder()->GetTemplateList();
        }


        $pdfImporterTemplates=[];
        if(function_exists('RNPDFImporter'))
        {
            $pdfImporterTemplates=RNPDFImporter()->GetTemplateList();
        }

        $pageRepository=new PageRepository($this->Loader);
        $previewManager=new PreviewManager();
        $templates=[];
        if($this->Loader->IsPR())
        {
            $templateRepository=new TemplateRepository($this->Loader);
            $templates=$templateRepository->GetList();
        }

        $dbmanager=new DBManager();
        global $wpdb;
        $pages=$wpdb->get_results('select ID Id, post_title PostTitle from '.$wpdb->posts.' where post_type="page"');

        $rowTemplates=json_decode(file_get_contents($this->Loader->DIR.'RowTemplates/Templates.json'));

        $postTypes=[];


        foreach(get_post_types([],'objects') as  $currentType)
        {
            $postTypes[]=[
                "Id"=>$currentType->name,
                'Label'=>$currentType->label
            ];
        }


        $tags=[];
        foreach(get_tags(['hide_empty'=>false]) as $currentTag)
        {
            $tags[]=[
                'Id'=>$currentTag->slug,
                'Name'=>$currentTag->name
            ];
        }

        $categories=[];
        foreach(get_categories(['hide_empty'=>false]) as $currentCategory)
        {
            $categories[]=[
                'Id'=>$currentCategory->term_id,
                'Name'=>$currentCategory->name
            ];
        }

        $this->Loader->LocalizeScript('rnBuilderVar','pageBuilder','pagebuilder',$this->Loader->AddAdvertisementParams(array(
            'FormList'=>$this->Loader->ProcessorLoader->FormProcessor->GetFormList(),
            'Pages'=>$pageRepository->GetPageList(),
            'ajaxurl'=>IntegrationURL::AjaxURL(),
            'TemplateURL'=>IntegrationURL::PageURL($this->Loader->Prefix),
            "IsPR"=>$this->Loader->IsPR(),
            'PurchaseURL'=>$this->Loader->GetPurchaseURL(),
            'TemplateData'=>$options,
            'Roles'=>$roles,
            'PluginURL'=>$this->Loader->URL,
            'PreviewURL'=>$previewManager->GetPreviewURL(),
            'SubPrefix'=>$this->Loader->GetSubPrefix(),
            'PDFBuilderTemplates'=>$pdfBuilderTemplates,
            'PDFImporterTemplates'=>$pdfImporterTemplates,
            'Templates'=>$templates,
            'RowTemplates'=>$rowTemplates,
            'WPPages'=>$pages,
            'Indexed'=>get_option('pb_index_generated',false),
            'SettingsPage'=>admin_url("admin.php?page=rnpagebuilder_settings"),
            'PostTypes'=>$postTypes,
            'Categories'=>$categories,
            'Tags'=>$tags
        )));

        echo '<div id="app"></div>';

    }
}