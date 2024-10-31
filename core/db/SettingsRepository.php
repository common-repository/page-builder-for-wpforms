<?php


namespace rnpagebuilder\core\db;


use rnpagebuilder\core\db\core\OptionsManager;
use rnpagebuilder\core\db\core\RepositoryBase;

class SettingsRepository extends RepositoryBase
{
    public function GetLog()
    {
        $optionsManager=new OptionsManager($this->Loader);
        $log=$optionsManager->GetOption('RNPageBuilderLog');
        if($log=='')
            return (object)array(
                'Enable'=>false,
                "LogType"=>"0"
            );
        return $log;
    }
}