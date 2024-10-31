<?php


namespace rnpagebuilder\core\Integration\Processors\Entry\EntryItems;


use Exception;
use rnpagebuilder\core\Integration\Processors\Entry\EntryItems\Core\EntryItemBase;
use rnpagebuilder\pr\core\Parser\Elements\ParseMain;

class FormulaEntryItem extends EntryItemBase
{

    /** @var ParseMain */
    public $Parser;
    public function __construct($parser)
    {
        parent::__construct();
        $this->Parser=$parser;

    }

    public function GetText()
    {
        return $this->Parser->ParseText();
    }

    protected function InternalGetObjectToSave()
    {
        throw new Exception('Method not implemented');
    }

    public function InitializeWithOptions($field, $options)
    {
        throw new Exception('Method not implemented');
    }

    public function GetHtml($style = 'standard')
    {
        return $this->GetText();
    }

    public function GetType()
    {
        return 'formula';
    }

    public function IsEmpty()
    {
        return $this->GetText()=='';
    }

    public function InternalGetDetails($base)
    {

        $base->Value=$this->GetText();
        return [$base];
    }


}