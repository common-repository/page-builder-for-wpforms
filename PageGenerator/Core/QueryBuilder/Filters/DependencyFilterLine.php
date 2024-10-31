<?php


namespace rnpagebuilder\PageGenerator\Core\QueryBuilder\Filters;


use rnpagebuilder\PageGenerator\Core\QueryBuilder\QueryElement\Dependency;

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