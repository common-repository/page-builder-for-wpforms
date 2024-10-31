<?php


namespace rnpagebuilder\PageBuilder\QueryBuilder\QueryBuilder\Filters;


use rnpagebuilder\PageBuilder\QueryBuilder\QueryElement\Dependency;

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