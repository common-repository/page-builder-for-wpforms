<?php


namespace rnpagebuilder\PageBuilderOld\Renderers\Blocks\SearchBarRenderer\SearchFieldTemplates;


use rnpagebuilder\DTO\ConditionGroupOptionsDTO;
use rnpagebuilder\DTO\ConditionLineOptionsDTO;
use rnpagebuilder\DTO\ConditionLineTypeEnumDTO;
use rnpagebuilder\DTO\ConditionTypeEnumDTO;
use rnpagebuilder\PageBuilderOld\DataSources\FormDataSource\FormDataSource;
use rnpagebuilder\PageBuilderOld\Renderers\Core\RendererBase;

class SearchInputTemplate extends SearchFieldTemplateBase
{

    public function __construct($loader, $twig,$field)
    {
        parent::__construct($loader, $twig,$field);
        $this->AddStyle('Input','Styles/Input.css');
    }


    protected function GetTemplateName()
    {
        return 'Blocks/SearchBarRenderer/SearchFieldTemplates/SearchInputTemplate.twig';
    }

    public function MaybeUpdateDataSource()
    {
        $postItem=$this->Field->GetFieldPostItem();
        if($postItem==null)
            return;

        /** @var FormDataSource $ds */
        $ds=$this->Field->SearchBar->GetDefaultDataSource();
        if($ds==null)
            return;

        $conditionGroup=new ConditionGroupOptionsDTO();
        $conditionGroup->Merge();
        $conditionGroup->ConditionLines=[(new ConditionLineOptionsDTO())->Merge()];
        $conditionGroup->ConditionLines[0]->FieldId=$this->Field->Options->FieldId;
        $conditionGroup->ConditionLines[0]->Type=ConditionLineTypeEnumDTO::$Standard;
        $conditionGroup->ConditionLines[0]->Value=$postItem->Value;
        $conditionGroup->ConditionLines[0]->Comparison=$this->Field->Options->ComparisonType;
        $conditionGroup->ConditionLines[0]->PathId=$this->Field->Options->PathId;
        $conditionGroup->ConditionLines[0]->SubType=$this->Field->Options->SubType;

        $this->Field->SearchBar->GetPageGenerator()->AddAdditionalFilters($conditionGroup);

    }


}