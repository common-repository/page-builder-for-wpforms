<?php


namespace rnpagebuilder\PageBuilderOld\QueryBuilder\QueryBuilder\Filters;


use rnpagebuilder\PageBuilderOld\QueryBuilder\QueryElement\Dependency;

class DependencyFilterLine extends FilterLineBase
{
    /** @var Dependency */
    public $Dependencies;

    /**
     * @param $dependency Dependency
     */
    public function AddDependency($dependency)
    {
        $this->Dependencies[]=$dependency;
        return $this;
    }
}