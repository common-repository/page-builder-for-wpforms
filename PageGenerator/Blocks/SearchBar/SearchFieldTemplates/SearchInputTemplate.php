<?php


namespace rnpagebuilder\PageGenerator\Blocks\SearchBar\SearchFieldTemplates;



use rnpagebuilder\DTO\ConditionGroupOptionsDTO;
use rnpagebuilder\DTO\ConditionLineOptionsDTO;
use rnpagebuilder\DTO\ConditionLineTypeEnumDTO;

class SearchInputTemplate extends SearchFieldTemplateBase
{

    public function __construct($loader, $twig,$field)
    {
        parent::__construct($loader, $twig,$field);
        $this->AddStyle('Input','Styles/Input.css');
    }


    protected function GetTemplateName()
    {
        return 'PageGenerator/Blocks/SearchBar/SearchFieldTemplates/SearchInputTemplate.twig';
    }

    public function MaybeUpdateDataSource()
    {

        if($this->Field->DefaultValue=='')
            return;

        $conditionGroup=new ConditionGroupOptionsDTO();
        $conditionGroup->Merge();
        $conditionGroup->ConditionLines=[(new ConditionLineOptionsDTO())->Merge()];
        $conditionGroup->ConditionLines[0]->FieldId=$this->Field->Options->FieldId;
        $conditionGroup->ConditionLines[0]->Type=ConditionLineTypeEnumDTO::$Standard;
        $conditionGroup->ConditionLines[0]->Value=$this->Field->DefaultValue;
        $conditionGroup->ConditionLines[0]->Comparison=$this->Field->Options->ComparisonType;
        $conditionGroup->ConditionLines[0]->PathId=$this->Field->Options->PathId;
        $conditionGroup->ConditionLines[0]->SubType=$this->Field->Options->SubType;

        $this->Field->SearchBar->GetPageGenerator()->AddAdditionalFilters($conditionGroup);


    }


}