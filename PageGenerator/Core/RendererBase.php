<?php


namespace rnpagebuilder\PageGenerator\Core;


use rnpagebuilder\core\LibraryManager;
use rnpagebuilder\core\Loader;
use rnpagebuilder\PageGenerator\TextRenderer\Core\ITextRendererParent;
use Twig\Environment;
use Twig\Markup;

abstract class RendererBase implements ITextRendererParent
{

    /** @var Loader */
    public $loader;
    /**
     * RendererBase constructor.
     * @param $twig Environment
     */
    public function __construct($loader)
    {
        $this->loader=$loader;
    }

    protected abstract function GetTemplateName();

    public function Render(){
        return $this->RenderTemplate($this->GetTemplateName(),$this);
    }

    public function GetTwig(){
        return $this->loader->GetTwigManager()->Twig;
    }

    public function RenderTemplate($templateName,$model)
    {
        return new Markup($this->loader->GetTwigManager()->Render($templateName,$model),'UTF-8');
    }



    public function AddStyle($hook,$path)
    {
        wp_enqueue_style($hook,$this->loader->URL.$path);
    }

    public function GetLoader(){
        return $this->loader;
    }
}