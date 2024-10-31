<?php


namespace rnpagebuilder\PageBuilderOld\Renderers\Blocks\SearchBarRenderer\SearchFieldTemplates;


use rnpagebuilder\core\Utils\ArrayUtils;
use rnpagebuilder\DTO\ConditionGroupOptionsDTO;
use rnpagebuilder\DTO\ConditionLineOptionsDTO;
use rnpagebuilder\DTO\ConditionLineTypeEnumDTO;
use rnpagebuilder\PageBuilderOld\DataSources\FormDataSource\FormDataSource;

class SearchUserTemplate extends SearchFieldTemplateBase
{
    public function __construct($loader, $twig,$field)
    {
        parent::__construct($loader, $twig,$field);
        $this->AddStyle('Input','Styles/Input.css');

        $this->FormField=$this->Field->SearchBar->GetPageRenderer()->GetFieldById($this->Field->Options->FieldId);

    }


    protected function GetTemplateName()
    {
        return 'Blocks/SearchBarRenderer/SearchFieldTemplates/SearchUserTemplate.twig';
    }

    public function IsOptionSelected($option)
    {
        $postItem=$this->Field->GetFieldPostItem();
        if($postItem==null)
            return false;

        foreach($postItem->Value as $currentValue)
        {
            if ($currentValue == $option)
                return true;
        }
        return false;
    }

    public function GetLabels(){
        $postItem=$this->Field->GetFieldPostItem();
        if($postItem==null)
            return [];
        return $postItem->Value;
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
        $conditionGroup->ConditionLines[0]->Value=ArrayUtils::Map($postItem->Value,function ($item){return $item->Id;});
        $conditionGroup->ConditionLines[0]->Comparison=$this->Field->Options->ComparisonType;
        $conditionGroup->ConditionLines[0]->PathId=$this->Field->Options->PathId;
        $conditionGroup->ConditionLines[0]->SubType=$this->Field->Options->SubType;

        $ds->AndAdditionalFilters[]=$conditionGroup;

        $a=1;
        // TODO: Implement MaybeUpdateDataSource() method.
    }
}