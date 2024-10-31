<?php


namespace rnpagebuilder\PageGenerator\Core\QueryBuilder\Filters;


use rnpagebuilder\PageGenerator\Core\QueryBuilder\Comparison\ComparisonBase;
use rnpagebuilder\PageGenerator\Core\QueryBuilder\QueryElement\Dependency;

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