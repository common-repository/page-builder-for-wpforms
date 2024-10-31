<?php


namespace rnpagebuilder\PageBuilder\Renderers\Core;


use rnpagebuilder\core\LibraryManager;
use rnpagebuilder\core\Loader;
use Twig\Environment;
use Twig\Markup;

abstract class RendererBase
{
    public static $DependencyHooks=[];
    static $CoreWasLoaded=false;
    /** @var Loader */
    public $loader;
    /** @var Environment */
    public $twig;
    /**
     * RendererBase constructor.
     * @param $twig Environment
     */
    public function __construct($loader,$twig)
    {
        $this->loader=$loader;
        $this->twig=$twig;
    }

    protected abstract function GetTemplateName();

    public function Render(){
        return $this->RenderTemplate($this->GetTemplateName(),$this);
    }

    public function RenderTemplate($templateName,$model)
    {
        return new Markup($this->twig->render($templateName,['Model'=>$model]),'UTF-8');
    }

    public function AddScript($hook,$path,$dep=[])
    {
        if(!self::$CoreWasLoaded)
        {
            LibraryManager::AddLoader();
            $this->loader->AddScript('rncore','js/dist/RNMainCore_bundle.js',['@loader']);
            $this->loader->AddScript('runnablepage','js/dist/RNMainRunnablePage_bundle.js',array('@rncore'));
        }

        self::$DependencyHooks[]='@'.$hook;

        for($i=0;$i<count($dep);$i++)
        {
           $dep[$i]=\str_replace('@',$this->loader->Prefix.'_',$dep[$i]);
        }

        $this->loader->AddScript($hook,$path,array_merge(array('@runnablepage'),$dep));
    }

    public function AddStyle($hook,$path)
    {
        wp_enqueue_style($hook,$this->loader->URL.$path);
    }

}