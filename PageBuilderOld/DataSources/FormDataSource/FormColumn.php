<?php


namespace rnpagebuilder\PageBuilderOld\DataSources\FormDataSource;


use rnpagebuilder\DTO\FormColumnOptionsDTO;
use rnpagebuilder\PageBuilderOld\DataSources\Core\ColumnBase;

class FormColumn extends ColumnBase
{
    /** @var FormDataSource */
    public $DataSource;
    /** @var FormColumnOptionsDTO */
    public $Options;
    public function __construct($formDataSource,$columnOptions)
    {
        parent::__construct($formDataSource,$columnOptions);

    }

}