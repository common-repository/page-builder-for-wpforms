<?php

namespace rnpagebuilder\PageGenerator\Core\QueryBuilder;

use rnpagebuilder\core\Exception\FriendlyException;
use rnpagebuilder\DTO\FormulaOptionsDTO;

class AggregationBuilder extends QueryBuilder
{
    /** @var FormulaOptionsDTO */
    public $Formula;
    public function __construct($Loader, $RootTable, $RootID, $rootConditions = [],$formula=null,$formId='',$pageGenerator=null)
    {
        parent::__construct($Loader, $RootTable, $RootID, $rootConditions,$formId,$pageGenerator);
        $this->Formula=$formula;
    }

    protected function GenerateColumnsSectionQuery($isCounting=false)
    {
        if(!in_array($this->Formula->FormulaType,['min','max','sum','count']))
            throw new FriendlyException('Invalid aggregation '.$this->Formula->FormulaType.' please check the formulas that you have and try again');

        if($this->Formula->FormulaType!='count')
            return ' select '.$this->Formula->FormulaType.'(aggregation.numericvalue)';
        else
            return ' select count('.esc_sql($this->RootID).'.entry_id)';
    }


}