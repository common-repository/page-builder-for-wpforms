<?php


namespace rnpagebuilder\PageBuilder\QueryBuilder\Filters;


use rnpagebuilder\PageBuilder\QueryBuilder\Comparison\ComparisonBase;
use rnpagebuilder\PageBuilder\QueryBuilder\QueryElement\Dependency;

class FilterLineBase
{
    /** @var Dependency[] */
    public $Dependencies;

    /** @var ComparisonBase */
    public $Filter;
    /** @var FilterGroup */
    public $FilterGroup;

    public function __construct($filterGroup)
    {
        $this->FilterGroup=$filterGroup;
        $this->Dependencies=[];
        $this->Filter=null;
    }

    /**
     * @param $dependency Dependency
     */
    public function HasDependency($dependency)
    {
        foreach($this->Dependencies as $currentDependency)
        {
            if($currentDependency->Id==$dependency->Id)
                return true;
        }

        return false;
    }


}