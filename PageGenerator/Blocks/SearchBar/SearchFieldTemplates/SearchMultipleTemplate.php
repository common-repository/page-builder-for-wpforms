<?php


namespace rnpagebuilder\PageGenerator\Blocks\SearchBar\SearchFieldTemplates;



use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\MultipleOptionsFieldSettings;
use rnpagebuilder\DTO\ConditionGroupOptionsDTO;
use rnpagebuilder\DTO\ConditionLineOptionsDTO;
use rnpagebuilder\DTO\ConditionLineTypeEnumDTO;

class SearchMultipleTemplate extends SearchFieldTemplateBase
{
    /** @var MultipleOptionsFieldSettings */
    public $FormField;
    public function __construct($loader, $twig,$field)
    {
        parent::__construct($loader, $twig,$field);
        $this->AddStyle('Input','Styles/Input.css');

        $this->FormField=$this->Field->SearchBar->GetPageGenerator()->EntryRetriever->GetFieldById($this->Field->Options->FieldId);

    }


    protected function GetTemplateName()
    {
        return 'PageGenerator/Blocks/SearchBar/SearchFieldTemplates/SearchMultipleTemplate.twig';
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
    public function MaybeUpdateDataSource()
    {
        if($this->Field->DefaultValue=='')
            return;


        $conditionGroup=new ConditionGroupOptionsDTO();
        $conditionGroup->Merge();
        $conditionGroup->ConditionLines=[(new ConditionLineOptionsDTO())->Merge()];
        $conditionGroup->ConditionLines[0]->FieldId=$this->Field->Options->FieldId;
        $conditionGroup->ConditionLines[0]->Type=ConditionLineTypeEnumDTO::$Standard;
        $conditionGroup->ConditionLines[0]->Value=[$this->Field->DefaultValue];
        $conditionGroup->ConditionLines[0]->Comparison=$this->Field->Options->ComparisonType;
        $conditionGroup->ConditionLines[0]->PathId=$this->Field->Options->PathId;
        $conditionGroup->ConditionLines[0]->SubType=$this->Field->Options->SubType;

        $this->Field->SearchBar->GetPageGenerator()->AddAdditionalFilters($conditionGroup);
    }
}