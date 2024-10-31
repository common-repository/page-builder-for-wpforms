<?php


namespace rnpagebuilder\PageBuilderOld\QueryBuilder\Comparison;


use DateTime;
use rnpagebuilder\PageBuilderOld\QueryBuilder\Comparison\ComparisonFormatter\ComparisonFormatterBase;
use rnpagebuilder\PageBuilderOld\QueryBuilder\Comparison\ComparisonFormatter\DateComparisonFormatter;
use rnpagebuilder\PageBuilderOld\QueryBuilder\Comparison\ComparisonFormatter\StringComparisonFormatter;
use rnpagebuilder\PageBuilderOld\QueryBuilder\Comparison\DateUnit\DateUnitBase;
use rnpagebuilder\PageBuilderOld\QueryBuilder\Comparison\DateUnit\DayUnit;

class DateFixedValueComparison extends FixedValueComparison
{

    public function __construct($Table, $Column, $Comparison, $Value, $ComparisonFormatter = null)
    {
        if ($ComparisonFormatter == null)
            $ComparisonFormatter = new DateComparisonFormatter();
        parent::__construct($Table, $Column, $Comparison, $Value, $ComparisonFormatter);
    }

    public function CreateComparison()
    {
        $value = $this->Value;

        if($this->Column=='value')
            $this->Column='datevalue';
        $leftSide = $this->Table . '.' . $this->Column;

        $value = new DayUnit($value);

        $today = new DateTime(date('Y-m-d', \current_time('timestamp')));
        /*
        switch ($value->Value->Id)
        {
            case 'today':
                $value = new DayUnit($today->getTimestamp());
                break;
            case 'startofweek':
                $value = new DayUnit(strtotime('last sunday', $today->getTimestamp()));
                break;
            case 'endofweek':
                $value = new DayUnit(strtotime('next sunday', $today->getTimestamp()));
                break;
            case 'startofmonth':
                $value = new DayUnit(strtotime('first day of this month', $today->getTimestamp()));
                break;
            case 'endofmonth':
                $value = new DayUnit(strtotime('last day of this month', $today->getTimestamp()));
                break;
            case 'xDaysFromNow':
                $options = $value->Value->AdditionalOptions;
                $numberOfUnits = $options->NumberOfUnits;
                if ($numberOfUnits > 0)
                    $numberOfUnits = '+' . $numberOfUnits;
                $value = new DayUnit(strtotime($numberOfUnits . ' days'));
                break;

            case 'xWeeksFromNow':
                $options = $value->Value->AdditionalOptions;
                $numberOfUnits = $options->NumberOfUnits;
                if ($numberOfUnits > 0)
                    $numberOfUnits = '+' . $numberOfUnits;
                $value = new DayUnit(strtotime($numberOfUnits . ' weeks'));
                break;

            case 'xMonthsFromNow':
                $options = $value->Value->AdditionalOptions;
                $numberOfUnits = $options->NumberOfUnits;
                if ($numberOfUnits > 0)
                    $numberOfUnits = '+' . $numberOfUnits;
                $value = new DayUnit(strtotime($numberOfUnits . ' months'));
                break;
        }
*/

        switch ($this->Comparison)
        {
            case 'Equal':
                return " (" . $leftSide . '>=' . $this->ComparisonFormatter->Format($value->GetStartOfUnit()) . ' && ' . $leftSide . ' < ' . $this->ComparisonFormatter->Format($value->GetEndOfUnit()) . ')';
            case 'NotEqual':
                return " not (" . $leftSide . '>=' . $this->ComparisonFormatter->Format($value->GetStartOfUnit()) . ' && ' . $leftSide . ' < ' . $this->ComparisonFormatter->Format($value->GetEndOfUnit()) . ')';
            case 'GreaterThan':
                return $leftSide . ' >= ' . $this->ComparisonFormatter->Format($value->GetStartOfUnit());
            case 'LessOrEqualThan':
                return $leftSide . ' < ' . $this->ComparisonFormatter->Format($value->GetEndOfUnit());

        }

        return parent::CreateComparisonString($leftSide, $this->ComparisonFormatter->Format($value->GetStartOfUnit()));
        /*
                if($value->Type=='Relative')
                {
                    $startDate=null;
                    $endDate=null;


                    switch ($value->Value->Id)
                    {
                        case 'today':
                            $startDate=$today;
                            $endDate=new DateTime($startDate->format('c'));
                            $endDate->modify('+1 day');

                            return " (" . $leftSide . '>=' . $this->ComparisonFormatter->Format($startDate->getTimestamp()) . ' && ' . $leftSide . ' < ' . $this->ComparisonFormatter->Format($endDate->getTimestamp()) . ')';

                        case 'startofweek':

                            $startDate=\strtotime('last sunday', $today->getTimestamp());
                            $endDate=\strtotime('next sunday', $today->getTimestamp());
                            return " (" . $leftSide . '>=' . $this->ComparisonFormatter->Format($startDate) . ' && ' . $leftSide . ' < ' . $this->ComparisonFormatter->Format($endDate) . ')';

                    }

                }*/


    }


}