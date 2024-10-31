<?php


namespace rnpagebuilder\PageBuilderOld\Renderers\Blocks\SearchBarRenderer;


use rnpagebuilder\DTO\FieldTypeEnumDTO;
use rnpagebuilder\DTO\SearchFieldOptionsDTO;
use rnpagebuilder\PageGenerator\Core\RendererBase;
use rnpagebuilder\pr\PageGenerator\Blocks\SearchBar\SearchBarBlock;
use rnpagebuilder\pr\PageGenerator\Blocks\SearchBar\SearchFieldTemplates\SearchDateTemplate;
use rnpagebuilder\pr\PageGenerator\Blocks\SearchBar\SearchFieldTemplates\SearchFieldTemplateBase;
use rnpagebuilder\pr\PageGenerator\Blocks\SearchBar\SearchFieldTemplates\SearchInputTemplate;
use rnpagebuilder\pr\PageGenerator\Blocks\SearchBar\SearchFieldTemplates\SearchMultipleTemplate;
use rnpagebuilder\pr\PageGenerator\Blocks\SearchBar\SearchFieldTemplates\SearchTimeTemplate;
use rnpagebuilder\pr\PageGenerator\Blocks\SearchBar\SearchFieldTemplates\SearchUserTemplate;

class SearchFieldRenderer extends RendererBase
{
    /** @var SearchFieldOptionsDTO */
    public $Options;
    /** @var SearchBarBlock */
    public $SearchBar;
    /** @var SearchFieldTemplateBase */
    public $Input;
    public $PostItem;
    public function __construct($loader, $twig,$searchBar,$searchFieldOptions)
    {
        parent::__construct($loader, $twig);
        $this->SearchBar=$searchBar;
        $this->Options=$searchFieldOptions;
        $postItems=$this->SearchBar->GetPageGenerator()->GetFieldPostItem($this->SearchBar);
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
                $this->Input=(new SearchMultipleTemplate($this->loader,$this->GetTwig(),$this));
                break;
            case FieldTypeEnumDTO::$Date:
            case FieldTypeEnumDTO::$DateTime:
                $this->Input=(new SearchDateTemplate($this->loader,$this->GetTwig(),$this));
                break;
            case FieldTypeEnumDTO::$Time:
                $this->Input=(new SearchTimeTemplate($this->loader,$this->GetTwig(),$this));
                break;
            case FieldTypeEnumDTO::$User:
                $this->Input=(new SearchUserTemplate($this->loader,$this->GetTwig(),$this));
                break;
            default:
                $this->Input=(new SearchInputTemplate($this->loader,$this->GetTwig(),$this));

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