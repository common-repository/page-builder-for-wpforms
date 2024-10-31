<?php


namespace rnpagebuilder\PageGenerator\Blocks\SearchBar\SearchFieldTemplates;



use rnpagebuilder\core\LibraryManager;
use rnpagebuilder\DTO\ConditionGroupOptionsDTO;
use rnpagebuilder\DTO\ConditionLineOptionsDTO;
use rnpagebuilder\DTO\ConditionLineTypeEnumDTO;

class SearchDateTemplate extends SearchFieldTemplateBase
{
    public function __construct($loader, $twig,$field)
    {
        parent::__construct($loader, $twig,$field);
        $this->AddStyle('Input','Styles/Input.css');
        $libraryManager=new LibraryManager($loader);
        $libraryManager->AddDate();
        $this->Field->SearchBar->AddScript('search-date','js/dist/RNPBRunnableSearchDate_bundle',$libraryManager->GetDependencyHooks());


    }

    public function GetUsedDate(){
        $postItem=$this->Field->GetFieldPostItem();
        if($postItem==null)
            return '';

        return $postItem->Value->Date;


    }

    public function EnableTimePicker(){
        $field=$this->Field->SearchBar->GetPageGenerator()->EntryRetriever->GetFieldById($this->Field->Options->FieldId);
        if($field==null||$field->Mode=='Time'||$field->Mode=='DateTime')
            return 'true';
        return 'false';
    }


    public function EnableDatePicker(){
        $field=$this->Field->SearchBar->GetPageGenerator()->EntryRetriever->GetFieldById($this->Field->Options->FieldId);
        if($field==null||$field->Mode=='Date'||$field->Mode=='DateTime')
            return 'true';
        return 'false';
    }

    protected function GetTemplateName()
    {
        return 'PageGenerator/Blocks/SearchBar/SearchFieldTemplates/SearchDateTemplate.twig';
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
        $conditionGroup->ConditionLines[0]->Value=floatval($this->Field->DefaultValue);
        $conditionGroup->ConditionLines[0]->Comparison=$this->Field->Options->ComparisonType;
        $conditionGroup->ConditionLines[0]->PathId=$this->Field->Options->PathId;
        $conditionGroup->ConditionLines[0]->SubType=$this->Field->Options->SubType;

        $this->Field->SearchBar->GetPageGenerator()->AddAdditionalFilters($conditionGroup);
    }
}