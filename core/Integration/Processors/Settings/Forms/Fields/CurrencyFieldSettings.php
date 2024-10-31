<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/21/2019
 * Time: 5:05 AM
 */

namespace rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields;
class CurrencyFieldSettings extends FieldSettingsBase
{

    public function GetType()
    {
        return "Currency";
    }

    public function ParseValue($value, $pathId = null)
    {
        $value= parent::ParseValue($value, $pathId);
        if($pathId=='Value')
            return html_entity_decode($value);

        return $value;
    }


}