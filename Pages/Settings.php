<?php


namespace rnpagebuilder\Pages;


use rnpagebuilder\core\db\SettingsRepository;
use rnpagebuilder\core\Integration\IntegrationURL;
use rnpagebuilder\core\LibraryManager;
use rnpagebuilder\core\PageBase;
use rnpagebuilder\pr\Utilities\Activator;

class Settings extends PageBase
{

    public function Render()
    {
        $libraryManager=new LibraryManager($this->Loader);
        $libraryManager->AddCalendar();
        $libraryManager->AddCore();
        $libraryManager->AddCoreUI();
        $libraryManager->AddTabs();
        $libraryManager->AddInputs();
        $libraryManager->AddSpinner();
        $libraryManager->AddDialog();
        $this->Loader->AddScript('runnablepageBuilder','js/dist/RNPBSettings_bundle.js',$libraryManager->GetDependencyHooks());
        $this->Loader->AddStyle('runnablepageBuilder','js/dist/RNPBSettings_bundle.css');


        $settingsRepository=new SettingsRepository($this->Loader);
        global $wpdb;
        $lisense='';
        $licenseURL='';
        if($this->Loader->IsPR())
        {
            $lisense = (Activator::GetLicense($this->Loader));
            if($lisense!='')
            {
                $licenseURL = $lisense->URL;
                $lisense=$lisense->LicenseKey;
            }
        }

        $this->Loader->LocalizeScript('rnSettings','runnablepageBuilder','settings',array(
            "LicenseKey"=>$lisense,
            "Prefix"=>$this->Loader->Prefix,
            'ajaxurl'=>IntegrationURL::AjaxURL(),
            "ItemId"=>$this->Loader->GetConfig('ItemId'),
            'BaseUrl'=>get_home_url(),
            'LicenseURL'=>$licenseURL,
            'LicenseServer'=>$this->Loader->GetConfig('UpdateURL'),
            "IsPr"=>$this->Loader->IsPR(),
            'LogOptions'=>$settingsRepository->GetLog()
        ));

        echo '<div id="App"></div>';

    }
}