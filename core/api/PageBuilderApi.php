<?php

namespace rnpagebuilder\core\api;

use rnpagebuilder\core\db\PageRepository;
use rnpagebuilder\core\Loader;
use rnpagebuilder\core\Managers\ExceptionManager\ExceptionManager;
use rnpagebuilder\core\Repository\EntryMetaRepository;
use rnpagebuilder\PageGenerator\Core\PageGenerator;
use rnpagebuilder\PageGenerator\Managers\MessageManager;
use rnpagebuilder\pr\Api\SingleEntryApi;
use rnpagebuilder\Utilities\Sanitizer;

class PageBuilderApi
{
    /** @var Loader */
    public $loader;
    /** @var PageBuilderApi */
    private static $instance=null;

    private function __construct()
    {
        $this->loader=Loader::$Instance;
    }

    public static function GetInstance(){
        if(self::$instance==null)
            self::$instance=new PageBuilderApi();

        return self::$instance;
    }

    public function EntryMetaRepository(){
        return new EntryMetaRepository($this->loader);
    }

    public function ShowPage($pageId,$options=null)
    {
        try
        {
            return self::GetGenerator($pageId, $options)->Generate();
        }catch (\Exception $e)
        {
            $exceptionManager=new ExceptionManager($this,$e);
            return $exceptionManager->PrintErrorToScreen();
        }

    }

    public function GetGenerator($pageId, $options = null)
    {
        $repository = new PageRepository($this->loader);

        $pageBuilderOptions = $repository->GetPageById(intval($pageId));
        if ($pageBuilderOptions == null)
            return MessageManager::ShowErrorMessage(__("Template was not found"));

        $pageGenerator = PageGenerator::GetPageGenerator($this->loader, $pageBuilderOptions);
        $pageGenerator->InflateGetParameters(Sanitizer::GetValueFromPath($options, ['GetParameters'], null));
        if (isset($options['SkipInitialNonceValidation']) && $options['SkipInitialNonceValidation'] == true)
            $pageGenerator->SetSkipInitialNonceValidation();

        return $pageGenerator;

    }

    public function SingleEntry(){
        if($this->loader->IsPR())
            return new SingleEntryApi($this->loader);
        return null;
    }



}