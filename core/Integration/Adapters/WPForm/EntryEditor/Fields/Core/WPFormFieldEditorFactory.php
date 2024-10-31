<?php


namespace rnpagebuilder\core\Integration\Adapters\WPForm\EntryEditor\Fields\Core;


use rnpagebuilder\core\Integration\Adapters\WPForm\EntryEditor\Fields\WPComposedFieldEditor;
use rnpagebuilder\core\Integration\Adapters\WPForm\EntryEditor\Fields\WPDateTimeFieldEditor;
use rnpagebuilder\core\Integration\Adapters\WPForm\EntryEditor\Fields\WPFileUploadFieldEditor;
use rnpagebuilder\core\Integration\Adapters\WPForm\EntryEditor\Fields\WPMultipleChoiceFieldEditor;
use rnpagebuilder\core\Integration\Adapters\WPForm\EntryEditor\Fields\WPSignatureFieldEditor;
use rnpagebuilder\core\Integration\Adapters\WPForm\EntryEditor\Fields\WPSimpleTextFieldEditor;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\FieldSettingsBase;

class WPFormFieldEditorFactory
{
    /**
     * @param $row
     * @param $fieldSettings FieldSettingsBase
     * @param $properties
     */
    public static function GetFieldEditor($row,$fieldSettings,$properties)
    {
        switch ($fieldSettings->SubType)
        {
            case 'text':
            case 'textarea':
            case 'number':
            case 'email':
            case 'phone':
            case 'url':
            case 'password':
            case 'hidden':
            case 'number-slider':
            case 'rating':
            case 'payment-total':

                return new WPSimpleTextFieldEditor($row,$fieldSettings);
            case 'radio':
            case 'checkbox':
            case 'select':
            case 'payment-single':
            case 'payment-multiple':
            case 'payment-checkbox':
            case 'payment-select':

                return new WPMultipleChoiceFieldEditor($row,$fieldSettings);
            case 'name':
            case 'address':
                return new WPComposedFieldEditor($row,$fieldSettings);
            case 'date-time':
                return new WPDateTimeFieldEditor($row,$fieldSettings);
            case 'file-upload':
                return new WPFileUploadFieldEditor($row,$fieldSettings);
            case 'signature':
                return new WPSignatureFieldEditor($row,$fieldSettings);
        }
    }

}