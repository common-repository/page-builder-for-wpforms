<?php

namespace rnpagebuilder\PageGenerator\TextRenderer\Core;;


use rnpagebuilder\PageGenerator\Core\PageGenerator;
use rnpagebuilder\PageGenerator\Core\RendererBase;
use rnpagebuilder\PageGenerator\Core\TwigManager\TwigManager;
use rnpagebuilder\Utilities\Sanitizer;
use Twig\Markup;

abstract class TextRendererBase
{
    /** @var ITextRendererParent */
    public $Parent;
    public $Content;
    /** @var TwigManager */
    public static $TwigManager;
    /** @var RendererBase[] */
    public $Children;
    public $loader;
    /** @var PageGenerator */
    public $PageGenerator;

    /**
     * @param $content
     * @param $parent ITextRendererParent
     * @param $pageGenerator
     */
    public function __construct($content,$parent=null,$pageGenerator=null)
    {
        $this->loader=$parent->GetLoader();
        $this->Content=$content;
        $this->Parent=$parent;
        $this->Children=[];
        $this->PageGenerator=$pageGenerator;
        $this->ParseChildren();
    }

    public function GetEntryRetriever(){
        return $this->PageGenerator->EntryRetriever;
    }

    public function GetParent(){
        return $this->Parent;
    }

    public function GetAttributeValue($attributeName)
    {
        return Sanitizer::GetValueFromPath($this->Content,['attrs',$attributeName]);
    }

    public function GetStringAttribute($attributeName)
    {
        $value=$this->GetAttributeValue($attributeName);
        if($value==null)
            return '';
        return Sanitizer::SanitizeString($value);

    }

    public function GetLoader(){
        return $this->Parent->GetLoader();
    }


    public function Initialize(){
        $this->InternalInitialize();
        foreach ($this->Children as $currentChild)
            $currentChild->Initialize();
        return $this;
    }

    protected function InternalInitialize(){

    }

    public function GetTwigManager(){
        return $this->Parent->GetLoader()->GetTwigManager();
    }


    protected abstract function GetTemplateName();

    public function Render(){
        return $this->RenderTemplate($this->GetTemplateName(),$this);
    }

    public function RenderTemplate($templateName,$model)
    {
        if($templateName=='')
            throw new \Exception('Text Template not found');
        return new Markup($this->GetTwigManager()->Render($templateName,$model),'UTF-8');
    }

    public function GetContentChildren(){
        if($this->Content==null||!isset($this->Content->content))
            return [];

        $content=$this->Content->content;
        if(!is_array($content))
            return [$content];

        return $content;
    }

    private function ParseChildren()
    {
        $children=$this->GetContentChildren();
        foreach($children as $currentChild)
        {
            $this->Children[]=TextRenderFactory::GetRenderer($currentChild,$this,$this->PageGenerator);
        }

    }

    public function RenderChildren(){
        $markups='';
        foreach($this->Children as $currentChildren)
        {
            $markups.=strval($currentChildren->Render());
        }

        return new Markup($markups,'UTF-8');
    }

}