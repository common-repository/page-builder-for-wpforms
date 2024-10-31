<?php


namespace rnpagebuilder\PageBuilder\DataSources\FormDataSource;


use rnpagebuilder\DTO\FormColumnOptionsDTO;
use rnpagebuilder\PageBuilder\DataSources\Core\ColumnBase;

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