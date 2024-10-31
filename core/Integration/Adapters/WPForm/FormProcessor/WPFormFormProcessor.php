<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/19/2019
 * Time: 11:39 AM
 */

namespace rnpagebuilder\core\Integration\Adapters\WPForm\FormProcessor;



use rnpagebuilder\core\Integration\Processors\FormProcessor\FormProcessorBase;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\EmailNotification;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\ComposedFieldItem;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\ComposedFieldSettings;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\CurrencyFieldSettings;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\CurrencyMultipleOptionsFieldSettings;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\DateFieldSettings;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\DateTimeFieldSettings;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\EntryFieldSettings;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\FileUploadFieldSettings;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\HtmlFieldSettings;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\LikertScaleFieldSettings;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\MultipleOptionsFieldSettings;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\NumberFieldSettings;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\FieldSettingsBase;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\RatingFieldSettings;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\SignatureFieldSettings;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\SubmissionDateFieldSettings;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\TextFieldSettings;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\TimeFieldSettings;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\UserFieldSettings;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\FormSettings;

class WPFormFormProcessor extends FormProcessorBase
{
    public function __construct($loader)
    {
        parent::__construct($loader);
        \add_action('wpforms_save_form',array($this,'FormIsSaving'),10,2);
    }

    public function FormIsSaving($formId,$forms){
        $forms['post_content']=\stripslashes($forms['post_content']);
        $forms=$this->SerializeForm($forms);
        $this->SaveOrUpdateForm($forms);
    }


    public function SerializeForm($forms){
        $fieldList=\json_decode( ($forms['post_content']));
        $formSettings=new FormSettings();
        $formSettings->Id=$forms['ID'];

        if(isset($fieldList->fields))
            $fieldList=$fieldList->fields;
        else
            $fieldList=array();


        $formSettings->OriginalId=$forms['ID'];
        $formSettings->Name=$forms['post_title'];
        $formSettings->Fields=$this->SerializeFields($fieldList);
        $formSettings->Fields[]=(new EntryFieldSettings())->Initialize('__entryId','Entry','');
        $formSettings->Fields[]=(new UserFieldSettings())->Initialize('__userId','Submitted By','');
        $formSettings->Fields[]=(new DateTimeFieldSettings())->Initialize('__date','Submission Date','DateTime');

        return $formSettings;
    }

    public function SerializeFields($fieldList)
    {
        /** @var FieldSettingsBase[] $fieldSettings */
        $fieldSettings=array();
        foreach($fieldList as $field)
        {
            switch($field->type)
            {
                case 'text':
                case 'email':
                case 'password':
                case "phone":
                case "hidden":
                case 'textarea':
                case 'url':
                $settings=(new TextFieldSettings())->Initialize($field->id,isset($field->label)?$field->label:'',$field->type,$field);
                switch ($field->type)
                {
                    case 'textarea':
                        $settings->RendererType='TextArea';
                        break;
                    default:
                        $settings->RendererType='Text';
                        break;
                }
                $fieldSettings[]=$settings;
                    break;
                case 'html':
                case 'richtext':
                    $settings=(new HtmlFieldSettings())->Initialize($field->id,isset($field->label)?$field->label:'',$field->type,$field);
                    $fieldSettings[]=$settings;
                    break;
                case 'radio':
                case 'checkbox':
                case 'select':
                    $settings=(new MultipleOptionsFieldSettings())->Initialize($field->id,$field->label,$field->type,$field);
                    foreach($field->choices as $choice)
                    {
                        $settings->AddOption($choice->label,$choice->value);
                    }
                    switch ($field->type)
                    {
                        case 'radio':
                        case 'payment-multiple':
                            $settings->RendererType='Radio';
                            break;
                        case 'checkbox':
                        case 'payment-checkbox':
                            $settings->RendererType='Checkbox';
                            break;
                        case 'select':
                        case 'payment-select':
                            $settings->RendererType='Select';
                            break;
                    }

                $fieldSettings[]=$settings;
                    break;
                case 'payment-multiple':
                case 'payment-select':
                case 'payment-checkbox':
                    $settings=(new CurrencyMultipleOptionsFieldSettings())->Initialize($field->id,$field->label,$field->type,$field);
                    foreach($field->choices as $choice)
                    {
                        $settings->AddOption($choice->label,$choice->value);
                    }
                    switch ($field->type)
                    {
                        case 'radio':
                        case 'payment-multiple':
                            $settings->RendererType='Radio';
                            break;
                        case 'checkbox':
                        case 'payment-checkbox':
                            $settings->RendererType='Checkbox';
                            break;
                        case 'select':
                        case 'payment-select':
                            $settings->RendererType='Select';
                            break;
                    }

                    $fieldSettings[]=$settings;
                    break;
                case 'payment-single':
                case 'payment-total':
                    $field=(new CurrencyFieldSettings())->Initialize($field->id,$field->label,$field->type,$field);
                    $field->RendererType='Number';
                    $fieldSettings[]=$field;
                    break;
                case 'rating':
                    $field=(new RatingFieldSettings())->Initialize($field->id,$field->label,$field->type,$field);
                    $field->RendererType='Rating';
                    $fieldSettings[]=$field;
                    break;
                case 'number':
                case 'number-slider':
                    $field=(new NumberFieldSettings())->Initialize($field->id,$field->label,$field->type,$field);
                    $field->RendererType='Number';
                    $fieldSettings[]=$field;
                    break;
                case 'name':
                    $nameSettings=(new ComposedFieldSettings())->Initialize($field->id,$field->label,$field->type,$field);
                    switch ($field->format)
                    {
                        case 'simple':
                            $nameSettings->AddItem('Name','value','Name');
                            break;
                        case 'first-last':
                            $nameSettings->AddItem('FirstName','first','First Name');
                            $nameSettings->AddItem('LastName','last','Last Name');
                            break;
                        case 'first-middle-last':
                            $nameSettings->AddItem('FirstName','first','First Name');
                            $nameSettings->AddItem('MiddleName','middle','Middle Name');
                            $nameSettings->AddItem('LastName','last','Last Name');
                            break;
                    }

                    $fieldSettings[]=$nameSettings;
                    break;
                case 'address':

                    $addressSettings=(new ComposedFieldSettings())->Initialize($field->id,$field->label,$field->type,$field);
                    $addressSettings->CreateRow();
                    $addressSettings->AddComposedFieldItem((new ComposedFieldItem(null,'Address1','address1','Address 1'))->AddCommaBefore());
                    $addressSettings->CreateRow();
                    $addressSettings->AddComposedFieldItem((new ComposedFieldItem(null,'Address2','address2','Address 2'))->AddCommaBefore());
                    $addressSettings->CreateRow();
                    $addressSettings->AddComposedFieldItem((new ComposedFieldItem(null,'City','city','City'))->AddCommaBefore());
                    $addressSettings->AddComposedFieldItem((new ComposedFieldItem(null,'State','state','State'))->AddCommaBefore());
                    $addressSettings->CreateRow();
                    $addressSettings->AddComposedFieldItem((new ComposedFieldItem(null,'Postal','postal','Postal'))->AddCommaBefore());


                    if($field->scheme=='international')
                    {
                        $addressSettings->AddComposedFieldItem((new ComposedFieldItem(null,'Country','country','Country'))->AddCommaBefore());
                    }
                    $fieldSettings[]=$addressSettings;
                    break;
                case 'date-time':
                    switch ($field->format)
                    {
                        case 'date-time':
                            $dateSettings=(new DateTimeFieldSettings())->Initialize($field->id,$field->label,$field->type,$field)
                            ->SetDateFormat($field->date_format)
                            ->SetTimeFormat($field->time_format);
                            $fieldSettings[]=$dateSettings;
                            break;
                        case 'time':
                            $dateSettings=(new TimeFieldSettings())->Initialize($field->id,$field->label,$field->type,$field)
                            ->SetTimeFormat($field->time_format);

                            $fieldSettings[]=$dateSettings;
                            break;
                        case 'date':
                            $dateSettings=(new DateFieldSettings())->Initialize($field->id,$field->label,$field->type,$field)
                            ->SetDateFormat($field->date_format);
                            $fieldSettings[]=$dateSettings;
                            break;
                    }

                    break;
                case 'file-upload':
                    $fieldSettings[]=(new FileUploadFieldSettings())->Initialize($field->id,$field->label,$field->type,$field);
                    break;
                case 'signature':
                    $fieldSettings[]=(new SignatureFieldSettings())->Initialize($field->id,isset($field->label)?$field->label:'',$field->type,$field);
                    break;
                case 'likert_scale':
                    $settings=(new LikertScaleFieldSettings())->Initialize($field->id,$field->label,$field->type);
                    if(!is_object($field->rows)||!is_object($field->columns))
                        break;
                    $settings->AddRows((array)$field->rows);
                    $settings->AddColumns((array)$field->columns);
                    $fieldSettings[]=$settings;
            }
        }

        return $fieldSettings;
    }

    public function SyncCurrentForms()
    {
        global $wpdb;
        $results=$wpdb->get_results("select id ID, post_title,post_content from ".$wpdb->posts." where post_type='wpforms'",'ARRAY_A');
        foreach($results as $form)
        {
            $form=$this->SerializeForm($form);
            $this->SaveOrUpdateForm($form);
        }
    }

    public function GetFormList()
    {
        global $wpdb;

        return $wpdb->get_results("select form.id Id, post.post_title Name, form.fields Fields,original_id OriginalId,notifications Notifications from ".$wpdb->posts. " post join ". $this->Loader->FormConfigTable." form on post.id=form.original_id");
    }
}