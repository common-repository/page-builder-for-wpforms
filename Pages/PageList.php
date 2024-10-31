<?php


namespace rnpagebuilder\Pages;


use rnpagebuilder\core\db\PageRepository;
use rnpagebuilder\core\LibraryManager;
use rnpagebuilder\core\PageBase;
use rnpagebuilder\core\Integration\IntegrationURL;

class PageList extends PageBase
{

    public function Render()
    {
        if(isset($_GET['templateId']))
        {
            $importer=new PageBuilder($this->Loader);
            $importer->Render();
            return;
        }

        $libraryManager=new LibraryManager($this->Loader);
        $libraryManager->AddCore();
        $libraryManager->AddTabs();
        $libraryManager->AddCoreUI();
        $libraryManager->AddWPTable();
        $libraryManager->AddPreMadeDialog();

        $this->Loader->AddScript('pagelist','js/dist/RNPBPageList_bundle.js',$libraryManager->GetDependencyHooks());
        $this->Loader->AddStyle('pagelist','js/dist/RNPBPageList_bundle.css');

        $pageRepository=new PageRepository($this->Loader);
        $this->Loader->LocalizeScript('rnListVar','pagelist','pageList',$this->Loader->AddAdvertisementParams(array(
            'TemplateList'=>$pageRepository->GetPageList(30,0),
            'PluginUrl'=>$this->Loader->URL,
            'Count' => $pageRepository->GetPageListCount(),
            'ajaxurl'=>IntegrationURL::AjaxURL(),
            'TemplateURL'=>IntegrationURL::PageURL($this->Loader->Prefix),
            "IsPR"=>$this->Loader->IsPR(),
            'PurchaseURL'=>$this->Loader->GetPurchaseURL()
        )));

        echo '<div id="app"></div>';
    }
}