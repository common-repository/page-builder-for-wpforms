<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/22/2019
 * Time: 5:03 AM
 */

namespace rnpagebuilder\core\Integration\Adapters\WPForm\Entry;

use rnpagebuilder\core\Integration\Adapters\WPForm\Loader\WPFormSubLoader;
use rnpagebuilder\core\Integration\Processors\Entry\EntryItems\CurrencyEntryItem;
use rnpagebuilder\core\Integration\Processors\Entry\EntryItems\CurrencyMultipleEntryItem;
use rnpagebuilder\core\Integration\Processors\Entry\EntryItems\HTMLEntryItem;
use rnpagebuilder\core\Integration\Processors\Entry\EntryItems\LikertScaleEntryItem;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\CurrencyMultipleOptionsFieldSettings;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\LikertScaleFieldSettings;
use rnpagebuilder\core\Repository\EntryRepository;
use rnpagebuilder\pr\Managers\EmailManager\EmailManager;
use rnpagebuilder\pr\Managers\EntryUpdater\EntryUpdater;
use rnpagebuilder\pr\PageGenerator\Templates\EntryToPostGenerator\EntryToPostGenerator;
use rnpagebuilder\Utilities\Sanitizer;
use WPForms\Pro\Admin\Entries;
use DateTime;
use DateTimeZone;
use Exception;
use rnpagebuilder\core\Integration\Adapters\WPForm\Entry\EntryItems\WPFormAddressEntryItem;
use rnpagebuilder\core\Integration\Adapters\WPForm\Entry\EntryItems\WPFormDateTimeEntryItem;
use rnpagebuilder\core\Integration\Adapters\WPForm\Entry\EntryItems\WPFormFileUploadEntryItem;
use rnpagebuilder\core\Integration\Adapters\WPForm\Entry\EntryItems\WPFormNameEntryItem;
use rnpagebuilder\core\Integration\Adapters\WPForm\Entry\Retriever\WPFormEntryRetriever;
use rnpagebuilder\core\Integration\Adapters\WPForm\FormProcessor\WPFormFormProcessor;
use rnpagebuilder\core\Integration\Processors\Entry\EntryItems\CheckBoxEntryItem;
use rnpagebuilder\core\Integration\Processors\Entry\EntryItems\ComposedEntryItem;
use rnpagebuilder\core\Integration\Processors\Entry\EntryItems\DateEntryItem;
use rnpagebuilder\core\Integration\Processors\Entry\EntryItems\DateTimeEntryItem;
use rnpagebuilder\core\Integration\Processors\Entry\EntryItems\DropDownEntryItem;
use rnpagebuilder\core\Integration\Processors\Entry\EntryItems\Core\EntryItemBase;
use rnpagebuilder\core\Integration\Processors\Entry\EntryItems\FileUploadEntryItem;
use rnpagebuilder\core\Integration\Processors\Entry\EntryItems\NumberEntryItem;
use rnpagebuilder\core\Integration\Processors\Entry\EntryItems\RadioEntryItem;
use rnpagebuilder\core\Integration\Processors\Entry\EntryItems\SignatureEntryItem;
use rnpagebuilder\core\Integration\Processors\Entry\EntryItems\SimpleTextEntryItem;
use rnpagebuilder\core\Integration\Processors\Entry\EntryItems\TimeEntryItem;
use rnpagebuilder\core\Integration\Processors\Entry\EntryProcessorBase;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\FieldSettingsBase;

class WPFormEntryProcessor extends EntryProcessorBase
{
    /** @var WPFormSubLoader */
    public $Loader;
    public function __construct($loader)
    {
        parent::__construct($loader);

        \add_action('wpforms_process_entry_save',array($this,'SaveEntry'),15,4);
        add_action('wpforms_pro_admin_entries_edit_submit_completed',array($this,'EditEntry'),10,4);
        add_action('automation_edit_completed',array($this,'EditEntry'),10,4);
        add_action('wpforms_ajax_submit_before_processing',array($this,'MaybeCancelSubmission'));
        \add_filter('wpforms_emails_send_email_data',array($this,'EmailProcessing'),10,2);

    }


    public function EmailProcessing($emailData,$wpform)
    {
        if($this->Loader->IsPR())
        {
            $emailManager=new EmailManager($this->Loader);
            $emailData['message'] =$emailManager->MaybeUpdateEmailBody($emailData['message'],$wpform->form_data['id'],$wpform->entry_id);
        }

        return $emailData;


    }


    public function MaybeCancelSubmission(){
        if(isset($_POST['page_builder_entry_id'])||isset($_POST['page_builder_nonce']))
        {
            if(!wp_verify_nonce($_POST['page_builder_nonce'],'pb_edit_entry_'.$_POST['page_builder_entry_id'])||!isset($_POST['wpforms'])||!isset($_POST['wpforms']['fields']))
                return wp_send_json_error(["errors"=>['general'=>__('Invalid request, please try again','page-builder-for-wpform')]]);


            $entryId=intval($_POST['page_builder_entry_id']);
            $fields=json_encode($_POST['wpforms']['fields']);

            $entryUpdater=new EntryUpdater($this->Loader);
            $entryUpdater->Update($entryId,$fields);

            if(isset($_POST['page_builder_return_url'])&&filter_var($_POST['page_builder_return_url'],FILTER_VALIDATE_URL))
                return wp_send_json_success(["redirect_url"=>$_POST['page_builder_return_url']]);
            else
                wp_send_json_success(["confirmation"=>__("Entry Updated Successfully")]);
        }


    }


    public function EditEntry($formData,$response,$uploadedfields,$entry){
        $formProcessor=new WPFormFormProcessor($this->Loader);
        $formSettings=$formProcessor->SerializeForm(array(
            "ID"=>$formData['id'],
            'post_title'=>'',
            'post_content'=>\json_encode(array('fields'=>$formData['fields']))
        ));

        if(!isset($entry->fields))
            return;

        if(is_array($entry->fields))
            $fields=$entry->fields;
        else
            $fields=\json_decode($entry->fields,true);
        foreach($uploadedfields as $key=>$value)
        {
            $fields[$key]=$value;
        }
        $serializeEntry=$this->SerializeEntry($fields,$formSettings,$formData);
        global $wpdb;
        $wpdb->delete($this->Loader->RECORDS_DETAIL_TABLE,array('entry_id'=>$entry->entry_id));
        $this->SaveDetails($entry->entry_id,$formSettings->Id,$serializeEntry);


    }


    public function SendSameProcess($sameProcess)
    {
        return true;
    }
    public function UpdateOriginalEntryId($entryId,$formData)
    {
        if(!isset($formData['fields']))
            return;
        global $RNWPCreatedEntry;
        if(!isset($RNWPCreatedEntry)||!isset($RNWPCreatedEntry['Entry']))
            return;

        global $wpdb;
        $wpdb->update($this->Loader->RECORDS_TABLE,array(
            'original_id'=>$entryId
        ),array('id'=>$RNWPCreatedEntry['EntryId']));

    }
    public function SaveEntry($fields,$entry,$formId,$formData,$entryId=0){

        if(isset($_POST['__pbentryid'])&&$entryId==0)
            return;

        $entryId=0;
        if(!$this->Loader->IsWPFormsPro())
        {
            global $wpdb;
            $wpdb->insert($this->Loader->EntryFreeTable,[
                'form_id'=>$formId,
                'starred'=>0,
                'fields'=>json_encode($fields),
                'date'=>date('c'),
                'user_id'=>$entry['author']
            ]);
            $entryId=$wpdb->insert_id;
        }else
            $entryId=wpforms()->process->entry_id;
        $formProcessor=new WPFormFormProcessor($this->Loader);
        $formSettings=$formProcessor->SerializeForm(array(
            "ID"=>$formData['id'],
            'post_title'=>'',
            'post_content'=>\json_encode(array('fields'=>$formData['fields']))
        ));

        $entryItems=$this->SerializeEntry($fields,$formSettings,$formData);
        $this->SaveDetails($entryId,$formSettings->Id,$entryItems);

        $this->MaybeCreatePost($formData,$entryId);
    }

    public function SerializeEntry($entry, $formSettings,$formData=null)
    {
        /** @var EntryItemBase $entryItems */
        $entryItems=array();
        foreach($entry as $key=>$value)
        {
            $currentField=null;
            foreach($formSettings->Fields as $field)
            {
                if($field->Id==$key)
                {
                    $currentField=$field;
                    break;
                }
            }

            if($currentField==null)
                continue;

            $found=false;
            switch ($currentField->Type)
            {
                case 'Currency':
                    $entryItems[]=(new CurrencyEntryItem())->Initialize($currentField)->SetValue($value['value'])->SetAmount(isset($value['amount'])?$value['amount']:'')->SetAmountRaw(isset($value['amount_raw'])?$value['amount_raw']:'');
                    break;
                case 'HTML':
                    $entryItems[]=(new HTMLEntryItem())->Initialize($currentField)->SetHTML($value['value']);
                    $found=true;
                    break;
                case 'Text':
                    $entryItems[]=(new SimpleTextEntryItem())->Initialize($currentField)->SetValue($value['value']);
                    $found=true;
                    break;
                case 'Number':
                case 'Rating':
                    $numberEntryItem=new NumberEntryItem();
                    $entryItems[]=(new NumberEntryItem())->Initialize($currentField)->SetValue($numberEntryItem->SanitizeRawValue($value));
                    $found=true;
                    break;
                case 'Composed':
                    $entryItems[]=(new ComposedEntryItem())->Initialize($currentField)->SetValue((object)$value);
                    $found=true;
                    break;
                case 'Date':
                    $entryItems[]=(new DateEntryItem())->Initialize($currentField)->SetUnix($value['unix'])->SetValue($value['value']);
                    $found=true;
                    break;
                case 'Time':
                    $entryItems[]=(new TimeEntryItem())->Initialize($currentField)->SetUnix(strtotime("01/01/1970 ". $value['value']))->SetValue($value['value']);
                    $found=true;
                    break;
                case 'DateTime':
                    $entryItems[]=(new DateTimeEntryItem())->Initialize($currentField)->SetUnix($value['unix'])->SetValue($value['value']);
                    $found=true;
                    break;
                case 'FileUpload':
                    $found=true;
                    $fileItem=(new FileUploadEntryItem());
                    $fileItem->Initialize($currentField);

                    if(!isset($value['value_raw'])||$value['value_raw']=='')
                    {
                        $found=true;
                        break;
                    }
                    foreach($value['value_raw'] as $currentValue)
                    {
                        if(!isset($currentValue['value'])||$currentValue['value']=='')
                            continue;

                        $file=$fileItem->CreateAndAddFile();
                        $file->URL=$currentValue['value'];

                        if(isset($currentValue['ext']))
                            $file->Extension=$currentValue['ext'];

                        if(isset($currentValue['name']))
                            $file->Name=$currentValue['name'];

                        if(isset($currentValue['type']))
                            $file->Mime=$currentValue['type'];

                    }



                    $entryItems[]=$fileItem;
                    $found=true;
                    break;
                case 'Signature':
                    $entryItems[]=(new SignatureEntryItem())->Initialize($currentField)->SetValue($value['value']);
                    $found=true;
                    break;
                case 'CurrencyMultiple':
                    $found=true;
                    $currencyMultipleItems=(new CurrencyMultipleEntryItem())->Initialize($currentField);
                    if(!isset($value['value']))
                        break;

                    $items=preg_split("/\r\n|\n|\r/", $value['value']);
                    foreach($items as $currentItem)
                    {
                        $sections=explode(';',$currentItem);
                        $currencyMultipleItems->AddItem($sections[0],count($sections)>=2?$sections[1]:0);
                    }

                    $currencyMultipleItems->SetAmountRaw(isset($value['amount_raw'])?$value['amount_raw']:'');
                    $currencyMultipleItems->SetAmount(isset($value['amount'])?$value['amount']:'');


                    $entryItems[]=$currencyMultipleItems;
                    $found=true;
                    break;
                case 'LikertScale':
                    $likert=(new LikertScaleEntryItem())->Initialize($currentField);
                    $found=true;
                    if($value['value_raw']=='')
                        break;
                    foreach($value['value_raw'] as $rowIndex=>$currentRow)
                    {
                        if(!is_array($currentRow))
                            $currentRow=[$currentRow];
                        foreach($currentRow as $columnIndex)
                        {
                            if(!isset($currentField->Rows[$rowIndex])||!!isset($currentField->Columns[$columnIndex]))
                            {
                                $likert->AddValue($currentField->Rows[$rowIndex],$currentField->Columns[$columnIndex]);
                            }

                        }
                    }
                    $entryItems[]=$likert;
                    $found=true;
                    break;
            }

            if($found)
                continue;

            switch($currentField->SubType)
            {
                case 'text':
                case 'email':
                case 'password':
                case "phone":
                case "hidden":
                case 'textarea':
                case 'url':
                case 'number':
                    $entryItems[]=(new SimpleTextEntryItem())->Initialize($currentField)->SetValue($value['value']);
                    break;
                case 'radio':
                    $value=$value['value'];
                    if($value=='')
                        break;
                    $value=\explode("\n",$value);
                    $entryItems[]=(new RadioEntryItem())->Initialize($currentField)->SetValue($value);
                    break;
                case 'checkbox':
                    $value=$value['value'];
                    if($value=='')
                        break;
                    $value=\explode("\n",$value);
                    $entryItems[]=(new CheckBoxEntryItem())->Initialize($currentField)->SetValue($value);
                    break;
                case 'select':
                    $value=$value['value'];
                    if($value=='')
                        break;
                    $value=\explode("\n",$value);
                    $entryItems[]=(new DropDownEntryItem())->Initialize($currentField)->SetValue($value);
                    break;



                case 'credit-card':

                    break;
                case 'name':
                    switch ($currentField->Format)
                    {
                        case 'simple':
                            $entryItems[]=(new WPFormNameEntryItem())->InitializeWithValues($currentField,$value['value'],'');
                            break;
                        case 'first-last':
                            $entryItems[]=(new WPFormNameEntryItem())->InitializeWithValues($currentField,$value['first'],$value['last']);
                            break;
                        case 'first-middle-last':
                            $entryItems[]=(new WPFormNameEntryItem())->InitializeWithValues($currentField,$value['first'],$value['last'],$value['middle']);
                            break;
                    }
                    break;
                case 'address':
                    $country='';
                    if(isset($value['country']))
                        $country=$value['country'];
                    $entryItems[]=(new WPFormAddressEntryItem())->InitializeWithValues($currentField,$value['address1'],
                        $value['address2'],$value['city'],$value['state'],$value['postal'],$country);
                    break;
                case 'date-time':

                    $time='';
                    $date='';
                    $unix=0;
                    if(isset($value['time'])&&$value['time']!='')
                    {
                        $time=$value['time'];
                        $dateObject=DateTime::createFromFormat('m/d/Y '.$currentField->TimeFormat,'1/1/1970 ' .$time,new DateTimeZone('UTC'));
                        $unix=$value['unix'];

                    }else{
                        $time='';
                    }
                    if(isset($value['date'])&&$value['date']!='')
                    {
                        $date=$value['date'];
                        $dateObject=DateTime::createFromFormat($currentField->DateFormat.' H:i:s:u',$value['date'] . "0:00:00:0",new DateTimeZone('UTC'));
                        if($dateObject!=false)
                        {
                            $unix+=$dateObject->getTimestamp();
                        }

                        $unix=$value['unix'];

                    }else{
                        $date='';
                    }

                    $entryItems[]=(new WPFormDateTimeEntryItem())->InitializeWithValues($currentField,$value['value'],$date,$time,$unix);


                    break;
                case 'file-upload':
                    $mime='';
                    $entryItems[]=(new WPFormFileUploadEntryItem())->InitializeWithValues($currentField, $value['value'],$value['file'],$value['ext'],$value['file_original']);
                    break;
            }
        }


        return $entryItems;

    }

    public function InflateEntryItem(FieldSettingsBase $field,$entryData)
    {
        $entryItem=null;
        switch ($field->Type)
        {
            case 'CurrencyMultiple':
                $entryItem=new CurrencyMultipleEntryItem();
                break;
            case 'HTML':
                $entryItem=new HTMLEntryItem();
                break;
            case 'Composed':
                $entryItem=(new ComposedEntryItem());
                break;
            case 'Date':
                $entryItem=(new DateEntryItem());
                break;
            case 'Time':
                $entryItem=(new TimeEntryItem());
                break;
            case 'DateTime':
                $entryItem=(new DateTimeEntryItem());
                break;
            case 'FileUpload':
                $entryItem=(new FileUploadEntryItem());
                break;
            case 'Currency':
                $entryItem=(new CurrencyEntryItem());
                break;
        }


        if($entryItem==null)
        {
            switch ($field->SubType)
            {
                case 'text':
                case 'email':
                case 'password':
                case "phone":
                case "hidden":
                case 'payment-single':
                case 'textarea':
                case 'url':
                case 'number':
                    $entryItem = new SimpleTextEntryItem();
                    break;
                case 'radio':
                    $entryItem = new RadioEntryItem();
                    break;
                case 'checkbox':
                    $entryItem = new CheckBoxEntryItem();
                    break;
                case 'payment-multiple':
                    $entryItem = new CurrencyMultipleEntryItem();
                    break;
                case 'select':
                    $entryItem = new DropDownEntryItem();
                    break;
                case 'payment-select':
                    $entryItem = new DropDownEntryItem();
                    break;
                case 'credit-card':
                    break;
                case 'name':
                    $entryItem = new WPFormNameEntryItem();
                    break;
                case 'address':
                    $entryItem = new WPFormAddressEntryItem();
                    break;

                case 'date-time':
                    $entryItem = new WPFormDateTimeEntryItem();
                    break;
                case 'file-upload':
                    $entryItem = new WPFormFileUploadEntryItem();
                    break;
            }
        }

        if($entryItem==null)
            throw new Exception("Invalid entry sub type ".$field->SubType);
        $entryItem->InitializeWithOptions($field,$entryData);
        return $entryItem;
    }

    private function MaybeCreatePost($formData, $entryId)
    {
        global $wpdb;
        $generalSettings=$wpdb->get_results($wpdb->prepare('select id Id, general_settings GeneralSettiongs from '.$this->Loader->PageTable.' where type="EntryPost" and formid=%d',$formData['id']));
        foreach($generalSettings as $currentGeneralSettings)
        {
            $currentGeneralSettings->GeneralSettiongs=json_decode($currentGeneralSettings->GeneralSettiongs);

            if($currentGeneralSettings->GeneralSettiongs->Enable==false)
                continue;

            /** @var EntryToPostGenerator $generator */
            $generator=RNPB()->GetGenerator($currentGeneralSettings->Id,["GetParameters"=>[
                "entryid"=>$entryId
            ]]);

            $generator->CreatePost();

        }
    }


}