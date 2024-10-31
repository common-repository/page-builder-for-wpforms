<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/21/2019
 * Time: 5:02 AM
 */

namespace rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields;




class HtmlFieldSettings extends FieldSettingsBase
{
    public $MultipleLine=false;
    public $RendererType='Text';


    public function GetType()
    {
        return 'HTML';
    }

    public function ParseHTMLValue($value, $pathId = null)
    {
        return $this->ParseValue($value,$pathId);
    }

}