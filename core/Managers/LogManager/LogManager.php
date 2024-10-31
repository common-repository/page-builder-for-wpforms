<?php


namespace rnpagebuilder\core\Managers\LogManager;


use Exception;
use rnpagebuilder\core\db\SettingsRepository;
use rnpagebuilder\core\Integration\DateIntegration;
use rnpagebuilder\core\Integration\FileManager;
use rnpagebuilder\core\Loader;

class LogManager
{
    /** @var Loader */
    private static $loader;
    /** @var FileManager */
    private static $fileManager;
    /** @var DateIntegration */
    private static $dateIntegration;
    const TYPE_ERROR=10;
    const TYPE_DEBUG=5;
    /** @var $LogOptions */
    private static $LogOptions=null;
    private static $WasInitialized;
    static function Initialize($loader)
    {
        if(!self::$WasInitialized)
        {
            self::$loader = $loader;
            self::$fileManager = new FileManager($loader);
            self::$dateIntegration = new DateIntegration($loader);
        }
    }



    /**
     * @param $type "Error"|"Debug"|"Warning"
     * @param $message
     */
    static function Log($message,$type=LogManager::TYPE_DEBUG)
    {
        if($type!=LogManager::TYPE_DEBUG&&$type!=LogManager::TYPE_ERROR)
            throw new Exception('Invalid log type '.$type);

        if(!self::ShouldLog($type))
            return;

        if($type=self::TYPE_DEBUG)
            $type='DEBUG';
        if($type==self::TYPE_ERROR)
            $type='ERROR';

        $line=self::$dateIntegration->GetTimezonedDateFromUTCDate(date('c'))." - [".\strtoupper($type)."] --> ".$message."\r\n";

        $path=self::GetLogFilePath();

        \file_put_contents($path,$line,FILE_APPEND);


    }

    static function LogError($message)
    {
        self::Log($message,LogManager::TYPE_ERROR);
    }

    static function LogDebug($message)
    {
        self::Log($message,LogManager::TYPE_DEBUG);
    }

    private static function ShouldLog($type)
    {
        if(self::$LogOptions==null)
        {
            $settings=new SettingsRepository(self::$loader);
            self::$LogOptions=$settings->GetLog();
        }

        return self::$LogOptions->Enable&&(self::$LogOptions->LogType<=$type);
    }


    static function RemoveLog()
    {
        $path=self::GetLogFilePath();
        if(\file_exists($path))
            \unlink($path);
    }


    public static function GetLogFilePath()
    {
        return self::$fileManager->GetLoggerPath().'/log.txt';
    }


}