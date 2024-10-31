<?php


namespace rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields;


class ComposedFieldRow
{
    /** @var ComposedFieldItem[] */
    public $Items;
    /** @var ComposedFieldSettings */
    protected $Parent;
    public function __construct($parent)
    {
        $this->Parent=$parent;
        $this->Items=[];
    }

    public function InitializeFromOptions($currentRow)
    {
        $this->Items=[];
        foreach($currentRow->Items as $currentItem)
        {
            $composedItem=new ComposedFieldItem($this);
            $composedItem->InitializeFromOptions($currentItem);
            $this->Items[]=$composedItem;
        }

    }


}


