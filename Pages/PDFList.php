<?php


namespace rnpagebuilder\Pages;


use rnpagebuilder\core\db\PageRepository;
use rnpagebuilder\core\PageBase;
use rnpagebuilder\core\Integration\IntegrationURL;

class PDFList extends PageBase
{

    public function Render()
    {
        if(isset($_GET['templateId']))
        {
            $importer=new PageBuilder($this->Loader);
            $importer->Render();
            return;
        }

        $this->Loader->AddScript('loader','js/lib/loader.js',array('wp-element'));
        $this->Loader->AddScript('core','js/dist/RNMainCore_bundle.js',array('@loader'));
        $this->Loader->AddScript('coreui','js/dist/RNMainCoreUI_bundle.js',array('@loader','@core'));


        $this->Loader->AddScript('pdflist','js/dist/RNMainPDFList_bundle.js',array('@core','@coreui'));

        $this->Loader->AddStyle('core','js/dist/RNMainCoreUI_bundle.css');
        $this->Loader->AddStyle('importer','js/dist/RNMainPDFList_bundle.css');

        $pageRepository=new PageRepository($this->Loader);
        $this->Loader->LocalizeScript('rnListVar','pdflist','pagebuilder',$this->Loader->AddAdvertisementParams(array(
            'TemplateList'=>$pageRepository->GetPageList(),
            'ajaxurl'=>IntegrationURL::AjaxURL(),
            'TemplateURL'=>IntegrationURL::PageURL($this->Loader->Prefix),
            "IsPR"=>$this->Loader->IsPR(),
            'PurchaseURL'=>$this->Loader->GetPurchaseURL()
        )));

        echo '<div id="app"></div>';
    }
}