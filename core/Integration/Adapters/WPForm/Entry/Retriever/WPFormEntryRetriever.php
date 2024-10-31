<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/28/2019
 * Time: 4:30 AM
 */

namespace rnpagebuilder\core\Integration\Adapters\WPForm\Entry\Retriever;


use rnpagebuilder\core\Integration\Adapters\WPForm\Entry\WPFormEntryProcessor;
use rnpagebuilder\core\Integration\Adapters\WPForm\Settings\Forms\WPFormFieldSettingsFactory;
use rnpagebuilder\core\Integration\Processors\Entry\EntryItems\MultipleSelectionEntryItem\ListEntryItem\MultipleSelectionEntryItem;
use rnpagebuilder\core\Integration\Processors\Entry\EntryItems\MultipleSelectionEntryItem\ListEntryItem\MultipleSelectionValueItem;
use rnpagebuilder\core\Integration\Processors\Entry\EntryProcessorBase;
use rnpagebuilder\core\Integration\Processors\Entry\Retriever\EntryRetrieverBase;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\FieldSettingsFactoryBase;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\FormSettings;

class WPFormEntryRetriever extends EntryRetrieverBase
{


    /**
     * @return FieldSettingsFactoryBase
     */
    public function GetFieldSettingsFactory()
    {
        return new WPFormFieldSettingsFactory();
    }

    /**
     * @return EntryProcessorBase
     */
    protected function GetEntryProcessor()
    {
        return new WPFormEntryProcessor($this->Loader);
    }

    public function GetProductItems()
    {
        $items=array();
        foreach($this->EntryItems as $item)
        {
            switch ($item->Field->SubType)
            {
                case 'payment-select':
                case 'payment-multiple':
                    /** @var MultipleSelectionEntryItem $multipleItem */
                    $multipleItem=$item;

                    foreach($multipleItem->Items as $valueItem)
                    {
                        $items[]= array('name'=>$valueItem->Value,'price'=>$valueItem->Amount);
                    }
                break;
                case 'payment-single':
                $items[]=array('name'=>$item->Field->Label,'price'=>$item->Value);
                    break;
            }
        }

        return $items;
    }
}