<?php


namespace rnpagebuilder\PageBuilder\Renderers\Blocks\SearchBarRenderer\SearchFieldTemplates;


use rnpagebuilder\DTO\ConditionGroupOptionsDTO;
use rnpagebuilder\DTO\ConditionLineOptionsDTO;
use rnpagebuilder\DTO\ConditionLineTypeEnumDTO;
use rnpagebuilder\PageBuilder\DataSources\FormDataSource\FormDataSource;

class SearchTimeTemplate extends SearchFieldTemplateBase
{
    public function __construct($loader, $twig,$field)
    {
        parent::__construct($loader, $twig,$field);
        $this->AddStyle('Input','Styles/Input.css');

    }

    public function GetUsedTime(){
        $postItem=$this->Field->GetFieldPostItem();
        if($postItem==null)
            return '';

        return $postItem->Value->Time;


    }

    protected function GetTemplateName()
    {
        return 'Blocks/SearchBarRenderer/SearchFieldTemplates/SearchTimeTemplate.twig';
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
        $conditionGroup->ConditionLines[0]->Value=$postItem->Value->Unix;
        $conditionGroup->ConditionLines[0]->Comparison=$this->Field->Options->ComparisonType;
        $conditionGroup->ConditionLines[0]->PathId=$this->Field->Options->PathId;
        $conditionGroup->ConditionLines[0]->SubType=$this->Field->Options->SubType;

        $ds->AndAdditionalFilters[]=$conditionGroup;
    }
}