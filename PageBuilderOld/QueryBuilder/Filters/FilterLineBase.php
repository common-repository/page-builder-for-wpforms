<?php


namespace rnpagebuilder\PageBuilderOld\QueryBuilder\Filters;


use rnpagebuilder\PageBuilderOld\QueryBuilder\Comparison\ComparisonBase;
use rnpagebuilder\PageBuilderOld\QueryBuilder\QueryElement\Dependency;

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