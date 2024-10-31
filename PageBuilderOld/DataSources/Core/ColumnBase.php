<?php


namespace rnpagebuilder\PageBuilderOld\DataSources\Core;


use rnpagebuilder\DTO\ColumnBaseOptionsDTO;

class ColumnBase
{
    /** @var DataSourceBase */
    public $DataSource;
    /** @var ColumnBaseOptionsDTO */
    public $Options;

    public function __construct($dataSource,$options)
    {
        $this->DataSource=$dataSource;
        $this->Options=$options;
    }


}