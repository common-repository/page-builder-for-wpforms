<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/21/2019
 * Time: 5:05 AM
 */

namespace rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields;
use rnpagebuilder\Utilities\Sanitizer;
use Twig\Markup;

class RatingFieldSettings extends FieldSettingsBase
{

    public function GetType()
    {
        return "Rating";
    }

    public function ParseHTMLValue($value, $pathId = null)
    {
        $currentValue=Sanitizer::GetNumberValueFromPath($value,['value']);
        $scale=Sanitizer::GetNumberValueFromPath($value,['scale']);

        $imageUrl=RNPB()->loader->URL.'images/star.svg';
        $content='<div style="display: inline-flex;align-items: center;">';
        for($i=0;$i<$currentValue;$i++)
        {
            $content.='<img style="display:inline-block;height:15px;width:15px;" src="'.esc_attr($imageUrl).'"/> ';
        }

        $content.='('.esc_html($currentValue).'/'.esc_html($scale).')';
        $content.='</div>';


        return new Markup($content,'UTF-8');
    }


}