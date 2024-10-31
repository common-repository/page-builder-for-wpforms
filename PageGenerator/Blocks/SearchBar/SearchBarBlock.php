<?php

namespace rnpagebuilder\PageGenerator\Blocks\SearchBar;


use rnpagebuilder\DTO\SearchBarBlockOptionsDTO;
use rnpagebuilder\PageGenerator\Blocks\Core\BlockBase;

class SearchBarBlock extends BlockBase
{
    /** @var SearchBarBlockOptionsDTO */
    public $Options;
    /** @var SearchField[] */
    public $Fields;
    public $PostItems;

    public function __construct($column, $blockBaseOptions)
    {
        parent::__construct($column, $blockBaseOptions);

        $this->PostItems=$this->GetPageGenerator()->GetFieldPostItem($this);
        $this->AddScript('SearchBar','js/dist/RNPBRunnableSearchBar_bundle.js');
        $this->AddStyle('Button','Styles/Buttons.css');
        $this->Fields=[];
        $fieldOptions=[];
        foreach($this->Options->Fields as $currentField)
        {

            if($currentField->FieldId!=''&&$currentField->ComparisonType!='')
            {
                $this->Fields[] = new SearchField($this->loader, $this->GetTwig(), $this, $currentField);
                $fieldOptions[]=[
                    "Id"=>$currentField->Id,
                    'Type'=>$currentField->SubType
                ];
            }

        }

        $this->GetPageGenerator()->AddFieldOptions($this,['Fields'=>$fieldOptions]);

    }

    public function MaybeUpdateDataSource()
    {
        parent::MaybeUpdateDataSource();
        foreach ($this->Fields as $currentField)
            $currentField->MaybeUpdateDataSource();
    }


    protected function GetTemplateName()
    {
        return 'PageGenerator/Blocks/SearchBar/SearchBarBlock.twig';
    }
}