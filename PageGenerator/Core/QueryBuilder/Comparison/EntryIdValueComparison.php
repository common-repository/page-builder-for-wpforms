<?php


namespace rnpagebuilder\PageGenerator\Core\QueryBuilder\Comparison;


use rnpagebuilder\PageGenerator\Core\QueryBuilder\Comparison\ComparisonFormatter\ComparisonFormatterBase;
use rnpagebuilder\PageGenerator\Core\QueryBuilder\Comparison\ComparisonFormatter\NumericComparisonFormatter;
use rnpagebuilder\PageGenerator\Core\QueryBuilder\Filters\FilterLineBase;
use rnpagebuilder\Utilities\Sanitizer;

class EntryIdValueComparison extends ComparisonBase
{
    public $Table;
    public $Column;

    /** @var FilterLineBase */
    public $FilterLine;
    public $Value;
    /** @var ComparisonFormatterBase */
    public $ComparisonFormatter;
    /**
     * FixedValueComparison constructor.
     * @param $Table
     * @param $Column
     * @param $Comparison
     * @param $Value
     * @param $ComparisonFormatter ComparisonFormatterBase
     */
    public function __construct($FilterLine,$Table, $Column, $Comparison, $Value,$ComparisonFormatter=null)
    {
        $this->FilterLine=$FilterLine;
        $this->Table = $Table;
        $this->Column = $Column;
        $this->Comparison = $Comparison;
        $this->Value = $Value;
        $this->ComparisonFormatter=$ComparisonFormatter;

        if($this->ComparisonFormatter==null)
            $this->ComparisonFormatter=new NumericComparisonFormatter();
    }



    public function CreateComparison()
    {
        global $wpdb;
        return $this->CreateComparisonString($this->Table.'.'.$this->Column,$this->ComparisonFormatter->Format($this->Value));
    }

    public function CreateComparisonString($leftSide, $rightSide)
    {
        switch ($this->Comparison)
        {
            case 'IsStarred':
                return $this->FilterLine->FilterGroup->QueryBuilder->RootID.'.starred=1';
            case 'IsNotStared':
                return $this->FilterLine->FilterGroup->QueryBuilder->RootID.'.starred=0';
            case 'IsViewed':
                return $this->FilterLine->FilterGroup->QueryBuilder->RootID.'.viewed=1';
            case 'IsNotViewed':
                return $this->FilterLine->FilterGroup->QueryBuilder->RootID.'.viewed=0';
            case 'LastXEntries':
                $value=Sanitizer::SanitizeNumber($this->Value,0);
                $dbManager=$this->FilterLine->FilterGroup->QueryBuilder->Loader->GetDBManager();
                return $this->FilterLine->FilterGroup->QueryBuilder->RootID.'.id in (select * from(select id from '.$this->FilterLine->FilterGroup->QueryBuilder->Loader->RECORDS_TABLE.' records order by date limit 0,'.$dbManager->EscapeNumber($value).' ) as aux)';
            case 'LastXEntriesByUser':
                $userIntegration=$this->FilterLine->FilterGroup->QueryBuilder->Loader->GetUserIntegration();
                $userid=$userIntegration->GetCurrentUserId();
                if($userid==0)
                    return 'false';
                $value=Sanitizer::SanitizeNumber($this->Value,0);
                $userIntegration=$this->FilterLine->FilterGroup->QueryBuilder->Loader->GetUserIntegration();
                $dbManager=$this->FilterLine->FilterGroup->QueryBuilder->Loader->GetDBManager();
                return $this->FilterLine->FilterGroup->QueryBuilder->RootID.'.user_id = '.$userIntegration->GetCurrentUserId().' and '.$this->FilterLine->FilterGroup->QueryBuilder->RootID.'.id in (select * from(select id from '.$this->FilterLine->FilterGroup->QueryBuilder->Loader->RECORDS_TABLE.' records order by date limit 0,'.$dbManager->EscapeNumber($value).' ) as aux)';

        }

        return parent::CreateComparisonString($leftSide, $rightSide);
    }

}