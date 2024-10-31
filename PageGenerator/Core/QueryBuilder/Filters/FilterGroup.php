<?php


namespace rnpagebuilder\PageGenerator\Core\QueryBuilder\Filters;


use rnpagebuilder\PageGenerator\Core\QueryBuilder\QueryBuilder;
use rnpagebuilder\PageGenerator\Core\QueryBuilder\QueryElement\Dependency;

class FilterGroup
{
    /** @var FilterLineBase[] */
    public $FilterLines;

    /** @var QueryBuilder */
    public $QueryBuilder;
    public $JoinType;
    public function __construct($queryBuilder,$joinType='or')
    {
        $this->JoinType=$joinType;
        $this->FilterLines=[];
        $this->QueryBuilder=$queryBuilder;
    }

    /**
     * @param $line FilterLineBase
     */
    public function AddFilterLine($line)
    {
        $this->FilterLines[]=$line;
    }

    /**
     * @param array $addedDependencies
     * @return Dependency[]
     */
    public function CreateGroupString(){
        $group='(';

        for($i=0;$i<count($this->FilterLines);$i++)
        {
            $currentLine=$this->FilterLines[$i];
            if($i>0)
                $group.=' and ';


            $comparison=$currentLine->Filter->CreateComparison();
            $dependenciesToAdd=[];
            if($this->QueryBuilder!=null)
                foreach($currentLine->Dependencies as $currentDependency)
                {
                    if(!$this->QueryBuilder->HasDependency($currentDependency))
                    {
                        $dependenciesToAdd[]=$currentDependency;
                    }

                }

            if(count($dependenciesToAdd)>0)
                foreach($dependenciesToAdd as $dependency)
                {
                    $group.='exists('. $dependency->CreateSubQuery();
                    if($currentLine->Filter!=null)
                        $group.=' and '.$comparison;

                    $group.=' )';

                }
            else
                $group.=$comparison;
        }

        $group.=' ) ';
        return $group;
    }

}