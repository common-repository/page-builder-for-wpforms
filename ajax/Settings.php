<?php


namespace rnpagebuilder\ajax;


use rnpagebuilder\core\db\core\DBManager;
use rnpagebuilder\core\db\core\OptionsManager;
use rnpagebuilder\core\db\SettingsRepository;
use rnpagebuilder\core\Integration\Adapters\WPForm\FormProcessor\WPFormFormProcessor;
use rnpagebuilder\core\Managers\IndexGenerator;
use rnpagebuilder\core\Managers\LogManager\LogManager;
use rnpagebuilder\pr\Utilities\Activator;

class Settings extends AjaxBase
{

    function GetDefaultNonce()
    {
        return 'settings';
    }

    protected function RegisterHooks()
    {
        $this->RegisterPrivate('activate_license','ActivateLicense');
        $this->RegisterPrivate('deactivate_license','DeactivateLicense');
        $this->RegisterPrivate('index_records','IndexRecords');
        $this->RegisterPrivate('complete_indexing','CompleteIndexing');
        $this->RegisterPrivate('SaveLog','SaveLog');
        $this->RegisterPrivate('download_log','DownloadLog');
        $this->RegisterPrivate('delete_log','DeleteLog');
        $this->RegisterPrivate('get_total_records','GetTotalRecords');

    }

    public function GetTotalRecords(){
        global $wpdb;
        $indexGenerator=new IndexGenerator($this->Loader);
        $indexGenerator->PrepareForIndexGeneration();
        $this->SendSuccessMessage($wpdb->get_var("select count(*) from ".$wpdb->prefix."wpforms_entries"));
    }

    public function CompleteIndexing(){
        update_option('pb_index_generated',true);
        LogManager::LogDebug('Index Generation Completed');
        $this->SendSuccessMessage(true);
    }

    public function SaveLog(){
        $options=$this->GetRequired('Options');

        $optionsManager=new OptionsManager($this->Loader);
        $optionsManager->SaveOptions('RNPageBuilderLog',$options);
        $this->SendSuccessMessage(true);
    }

    public function IndexRecords(){

        set_error_handler(function ($errno, $errstr, $errfile, $errline){
            LogManager::LogError($errstr."\n".$errfile." ".$errline);
            echo $errstr;
        });

        $indexGenerator=new IndexGenerator($this->Loader);
        try{
            $indexGenerator->Generate($this->GetRequired('StartIndex'),$this->GetRequired('EndIndex'));
        }catch(\Exception $e)
        {
            LogManager::LogError($e->getTraceAsString());
        }


        $this->SendSuccessMessage('Index created successfully');
    }


    public function ActivateLicense(){

        $licenseKey=$this->GetRequired('LicenseKey');
        $expirationDate=$this->GetRequired('ExpirationDate');
        $url=$this->GetRequired('URL');
        (new Activator())->SaveLicense($this->Loader,$licenseKey,$expirationDate,$url);
        $this->SendSuccessMessage('');
    }

    public function DeactivateLicense(){
        Activator::DeleteLicense($this->Loader);

        $this->SendSuccessMessage('');
    }

    public function DeleteLog(){
        LogManager::RemoveLog();
        $this->SendSuccessMessage(true);
    }

    public function DownloadLog(){


        if(!\file_exists(LogManager::GetLogFilePath()))
        {
            echo "No log file found";
            die();
        }


        header('Content-Disposition: inline; filename="log.txt"');
        header("Content-Type: text");
        header("Content-Length: " . filesize(LogManager::GetLogFilePath()));
        echo (file_get_contents(LogManager::GetLogFilePath()));
    }

}