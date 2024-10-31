<?php


namespace rnpagebuilder\PageBuilder\Renderers\Blocks\SearchBarRenderer;


use rnpagebuilder\DTO\FieldTypeEnumDTO;
use rnpagebuilder\DTO\SearchFieldOptionsDTO;
use rnpagebuilder\PageBuilder\Renderers\Blocks\SearchBarRenderer\SearchFieldTemplates\SearchDateTemplate;
use rnpagebuilder\PageBuilder\Renderers\Blocks\SearchBarRenderer\SearchFieldTemplates\SearchFieldTemplateBase;
use rnpagebuilder\PageBuilder\Renderers\Blocks\SearchBarRenderer\SearchFieldTemplates\SearchInputTemplate;
use rnpagebuilder\PageBuilder\Renderers\Blocks\SearchBarRenderer\SearchFieldTemplates\SearchMultipleTemplate;
use rnpagebuilder\PageBuilder\Renderers\Blocks\SearchBarRenderer\SearchFieldTemplates\SearchTimeTemplate;
use rnpagebuilder\PageBuilder\Renderers\Blocks\SearchBarRenderer\SearchFieldTemplates\SearchUserTemplate;
use rnpagebuilder\PageBuilder\Renderers\Core\RendererBase;

class SearchFieldRenderer extends RendererBase
{
    /** @var SearchFieldOptionsDTO */
    public $Options;
    /** @var SearchBarRenderer */
    public $SearchBar;
    /** @var SearchFieldTemplateBase */
    public $Input;
    public $PostItem;
    public function __construct($loader, $twig,$searchBar,$searchFieldOptions)
    {
        parent::__construct($loader, $twig);
        $this->SearchBar=$searchBar;
        $this->Options=$searchFieldOptions;
        $postItems=$this->SearchBar->GetPageRenderer()->PageGenerator->GetFieldPostItem($this->SearchBar);
        $this->PostItem=null;
        if($postItems!=null)
        {
            foreach ($postItems->Fields as $currentField)
            {
                if($currentField->Id==$this->Options->Id)
                    $this->PostItem=$currentField;
            }
        }
        $field= $this->SearchBar->GetPageRenderer()->GetFieldById($this->Options->FieldId);
        if($field==null)
            return '';

        switch ($field->Type)
        {
            case FieldTypeEnumDTO::$Multiple:
                $this->Input=(new SearchMultipleTemplate($this->loader,$this->twig,$this));
                break;
            case FieldTypeEnumDTO::$Date:
            case FieldTypeEnumDTO::$DateTime:
                $this->Input=(new SearchDateTemplate($this->loader,$this->twig,$this));
                break;
            case FieldTypeEnumDTO::$Time:
                $this->Input=(new SearchTimeTemplate($this->loader,$this->twig,$this));
                break;
            case FieldTypeEnumDTO::$User:
                $this->Input=(new SearchUserTemplate($this->loader,$this->twig,$this));
                break;
            default:
                $this->Input=(new SearchInputTemplate($this->loader,$this->twig,$this));

        }
    }


    public function RenderInput(){
       return $this->Input->Render();
    }

    public function GetFieldPostItem(){
        $postItem=$this->SearchBar->GetBlockPostItem();
        if($postItem==null)
            return null;

        foreach($postItem->Fields as $currentField)
        {
            if($currentField->Id==$this->Options->Id)
                return $currentField;
        }

        return null;

    }

    public function MaybeUpdateDataSource()
    {
        $this->Input->MaybeUpdateDataSource();

    }



    protected function GetTemplateName()
    {
        return 'Blocks/SearchBarRenderer/SearchFieldRenderer.twig';
    }
}