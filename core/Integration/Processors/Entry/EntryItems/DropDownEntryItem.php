<?php


namespace rnpagebuilder\core\Integration\Processors\Entry\EntryItems;


use rnpagebuilder\core\Integration\Adapters\WPForm\Settings\Forms\Fields\WPFormAddressFieldSettings;
use rnpagebuilder\core\Integration\Processors\Entry\EntryItems\MultipleSelectionEntryItem\MultipleSelectionEntryItem;
use rnpagebuilder\core\Integration\Processors\Entry\HTMLFormatters\BasicPHPFormatter;
use rnpagebuilder\core\Integration\Processors\Entry\HTMLFormatters\MultipleBoxFormatter\SingleBoxFormatter;

class DropDownEntryItem extends MultipleSelectionEntryItem
{
    public function GetHtml($style = 'standard')
    {
        $value=implode(', ',$this->Values);
        if($style=='similar')
        {

            $formatter = new SingleBoxFormatter($value);

            return $formatter;
        }
        return new BasicPHPFormatter($value);
    }


}