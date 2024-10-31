<?php


namespace rnpagebuilder\core\Integration\Adapters\WPForm\Loader;
use rnpagebuilder\core\Integration\Adapters\WPForm\Entry\Retriever\WPFormEntryRetriever;
use rnpagebuilder\core\Loader;
use rnpagebuilder\pr\core\PRLoader;
use rnpagebuilder\pr\Managers\ConfirmationManager\ConfirmationManager;

class WPFormSubLoader extends Loader
{

    public $ItemId;
    public function __construct($prefix,$basePrefix,$dbVersion,$fileVersion,$rootFilePath,$config)
    {
        $this->ItemId=12;
        $this->ProcessorLoader=new WPFormProcessorLoader($this);
        $this->ProcessorLoader->Initialize();
        parent::__construct($prefix,$basePrefix,$dbVersion,$fileVersion,$rootFilePath,$config);
        \add_filter('wpforms_frontend_confirmation_message',array($this,'ProcessConfirmation'),10,2);
        $this->AddMenu('Page Builder for WPForms',$this->Prefix,'administrator','','rnpagebuilder\Pages\PageList');
        $this->AddMenu('Templates',$this->Prefix,'administrator','','rnpagebuilder\Pages\PageList');
        $this->AddMenu('Our WPForms Plugins',$prefix.'_additional_plugins','administrator','','rnpagebuilder\Pages\AdditionalPlugins');
        $this->AddMenu('Get help',$this->Prefix.'_help','administrator','','rnpagebuilder\Pages\Help');
        $this->AddMenu('Settings',$this->Prefix.'_settings','administrator','','rnpagebuilder\Pages\Settings');

        if($this->IsPR())
        {
            $this->PRLoader=new PRLoader($this);
        }
    }


    public function GetSubPrefix()
    {
        return 'WPForm';
    }

    public function IsWPFormsPro(){
        return function_exists( 'wpforms' ) && wpforms()->pro;
    }

    public function ProcessConfirmation($message,$formData)
    {
        if(!$this->IsPR())
            return $message;

        $entryId=wpforms()->process->entry_id;
        $confirmationManager=new ConfirmationManager($this);
        return $confirmationManager->ProcessConfirmationMessage($message,wpforms()->process->entry_id);
    }

    /**
     * @return WPFormEntryRetriever
     */
    public function CreateEntryRetriever()
    {
        return new WPFormEntryRetriever($this);
    }


    public function AddBuilderScripts()
    {
        $this->AddScript('wpformbuilder','js/dist/WPFormBuilder_bundle.js',array('jquery', 'wp-element','@builder'));
    }

    public function GetPurchaseURL()
    {
        return 'https://formwiz.rednao.com/page-builder-2/';
    }


    public function AddAdvertisementParams($params)
    {

        return $params;
    }

    public function GetProductItemId()
    {
        return 16;
    }


    public function GetRecordsTableName()
    {
        if($this->IsWPFormsPro())
            return $this->WPFormRecordTable;
        else
            return $this->EntryFreeTable;
    }
}