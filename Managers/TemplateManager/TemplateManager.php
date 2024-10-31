<?php

namespace rnpagebuilder\Managers\TemplateManager;

use rnpagebuilder\core\Loader;

class TemplateManager
{
    /** @var Loader */
    public $loader;
    private $zip;
    public function __construct($loader)
    {
        $this->loader=$loader;
        $this->zip=new \ZipArchive();
    }

    public function DownloadTemplate($templateId)
    {
        $path=$this->loader->DIR.'pr/RowTemplates/'.$templateId.'/Template.zip';
        if(!file_exists($path))
            return false;

        $this->zip=new \ZipArchive();
        $this->zip->open($path);
        $options=json_decode($this->zip->getFromName('Template.json'));
        return $options;
    }
}