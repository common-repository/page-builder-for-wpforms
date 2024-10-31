<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/21/2019
 * Time: 6:21 AM
 */

namespace rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields;

use rnpagebuilder\core\Utils\ArrayUtils;
use rnpagebuilder\core\Utils\ObjectSanitizer;
use rnpagebuilder\Utilities\Sanitizer;
use Twig\Markup;

class FileUploadFieldSettings extends FieldSettingsBase
{
    /**
     * FileUploadFieldSettings constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->RendererType='File';
    }

    public function GetType()
    {
        return 'FileUpload';
    }

    public function GetFiles($value){

        if(!isset($value->Value))
            return [];

        return ArrayUtils::Filter(ObjectSanitizer::Sanitize($value->Value,array((object)array(
            'URL'=>'',
            'Mime'=>'',
            'Name'=>''
        ))),function ($item){
            return $item->URL!='';
        });
    }

    public function IsImage($value)
    {
        $value=$this->GetFiles($value);

        return false;
    }

    public function ParseHTMLValue($value,$path=null)
    {
        if(!isset($value->value_raw)||!is_array($value->value_raw))
            return '';

        $text='';
        foreach($value->value_raw as $currentImage)
        {
            if(strpos($currentImage->type,'image')!==false)
            {
                $text.='<img style="max-width:100%; border:1px solid #dfdfdf;" img src="'.$currentImage->value.'"/>';
            }else{
                $text.='<a href="'.$currentImage->value.'">'.$currentImage->name.'</a>';
            }
        }

        return new Markup($text,'UTF-8');
    }

}