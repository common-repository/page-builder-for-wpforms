<?php


namespace rnpagebuilder\PageBuilder\QueryBuilder\Comparison;


use rnpagebuilder\PageBuilder\QueryBuilder\Comparison\ComparisonFormatter\ComparisonFormatterBase;
use rnpagebuilder\PageBuilder\QueryBuilder\Comparison\ComparisonFormatter\StringComparisonFormatter;

class FixedValueComparison extends ComparisonBase
{
    public $Table;
    public $Column;
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
    public function __construct($Table, $Column, $Comparison, $Value,$ComparisonFormatter=null)
    {
        $this->Table = $Table;
        $this->Column = $Column;
        $this->Comparison = $Comparison;
        $this->Value = $Value;
        $this->ComparisonFormatter=$ComparisonFormatter;

        if($this->ComparisonFormatter==null)
            $this->ComparisonFormatter=new StringComparisonFormatter();
    }


    public function CreateComparison()
    {
        global $wpdb;
        return $this->CreateComparisonString($this->Table.'.'.$this->Column,$this->ComparisonFormatter->Format($this->Value));
    }
}