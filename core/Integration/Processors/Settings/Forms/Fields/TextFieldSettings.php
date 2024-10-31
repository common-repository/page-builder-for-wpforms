<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/21/2019
 * Time: 5:02 AM
 */

namespace rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields;




class TextFieldSettings extends FieldSettingsBase
{
    public $MultipleLine=false;
    public $RendererType='Text';
    public function GetType()
    {
        return 'Text';
    }

}