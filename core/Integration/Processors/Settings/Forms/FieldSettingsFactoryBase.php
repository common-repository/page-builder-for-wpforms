<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/28/2019
 * Time: 4:21 AM
 */

namespace rnpagebuilder\core\Integration\Processors\Settings\Forms;


use rnpagebuilder\core\Integration\Processors\Entry\EntryItems\SignatureEntryItem;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\ComposedFieldSettings;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\DateFieldSettings;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\DateTimeFieldSettings;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\EntryFieldSettings;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\FieldSettingsBase;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\FileUploadFieldSettings;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\ListFieldSettings\ListFieldSettings;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\MultipleOptionsFieldSettings;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\NumberFieldSettings;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\RatingFieldSettings;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\SignatureFieldSettings;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\SubmissionDateFieldSettings;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\TextFieldSettings;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\TimeFieldSettings;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\UserFieldSettings;

abstract class FieldSettingsFactoryBase
{
    /**
     * @param $options
     * @return FieldSettingsBase
     */
    public function GetFieldByOptions($options)
    {
        /** @var FieldSettingsBase $field */
        $field=null;
        switch ($options->Type)
        {
            case 'Text':
                $field=new TextFieldSettings();
                break;
            case 'Number':
                $field=new NumberFieldSettings();
                break;
            case 'Multiple':
                $field=new MultipleOptionsFieldSettings();
                break;
            case 'FileUpload':
                $field=new FileUploadFieldSettings();
                break;
            case 'Composed':
                $field=new ComposedFieldSettings();
                break;
            case 'DateTime':
                $field=new DateTimeFieldSettings();
                break;
            case 'Date':
                $field=new DateFieldSettings();
                break;
            case 'Time':
                $field=new TimeFieldSettings();
                break;
            case 'List':
                $field=new ListFieldSettings();
                break;
            case 'User':
                $field=new UserFieldSettings();
                break;
            case 'EntryId':
                $field=new EntryFieldSettings();
                break;
            case 'Signature':
                $field=new SignatureFieldSettings();
                break;
            case 'Rating':
                $field=new RatingFieldSettings();
                break;


        }

        if($field!=null)
            $field->InitializeFromOptions($options);
        return $field;
    }
}