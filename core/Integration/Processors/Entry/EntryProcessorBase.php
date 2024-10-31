<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/22/2019
 * Time: 5:02 AM
 */

namespace rnpagebuilder\core\Integration\Processors\Entry;


use rnpagebuilder\core\Integration\Processors\Entry\EntryItems\Core\EntryItemBase;
use rnpagebuilder\core\Integration\Processors\Entry\EntryItems\DateTimeEntryItem;
use rnpagebuilder\core\Integration\Processors\Entry\EntryItems\SimpleTextEntryItem;
use rnpagebuilder\core\Integration\Processors\Entry\EntryItems\UserEntryItem;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\DateFieldSettings;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\FieldSettingsBase;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\TextFieldSettings;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\UserFieldSettings;
use rnpagebuilder\core\Loader;

abstract class EntryProcessorBase
{
    /** @var Loader */
    public $Loader;
    public abstract  function InflateEntryItem(FieldSettingsBase $field,$entryData);
    public function __construct($loader)
    {
        $this->Loader=$loader;
    }


    /**
     * @param $originalFormId
     * @param $entryItems
     * @param $originalEntryId
     * @param null $raw
     * @return int
     */
    public function SaveEntryToDB($originalFormId,&$entryItems,$originalEntryId,$raw=null){

        $itemsToSave=array();
        global $wpdb;
        $id=$wpdb->get_var($wpdb->prepare('select id from '.$this->Loader->FormConfigTable.' where original_id=%d',$originalFormId));
        $seqKey= $this->Loader->Prefix.'_seq_'.$id.'seq';
        $seqId=\get_option($seqKey,1);
        $reference=uniqid().bin2hex(openssl_random_pseudo_bytes(14));



        $entryItems[]=(new SimpleTextEntryItem())->Initialize(
            (new TextFieldSettings())->Initialize('___seq','Sequence Number','___seq')
        )->SetValue($seqId);

        $entryItems[]=(new UserEntryItem())->Initialize(
            (new UserFieldSettings())->Initialize('___usr','Sequence Number','___usr')
        )->SetUserId(\get_current_user_id());




        foreach($entryItems as $item)
        {
            $itemsToSave[]=$item->GetObjectToSave();
        }




        if($id===false)
            return 0;

        $entryId=null;
        if($originalEntryId!=null&& $originalEntryId!='')
            $entryId=$wpdb->get_var($wpdb->prepare('select id from '.$this->Loader->RECORDS_TABLE.' where original_id=%s',$originalEntryId));
        if($entryId!=null)
        {
            $wpdb->update($this->Loader->RECORDS_TABLE,array('entry'=>\json_encode($itemsToSave)),array('id'=>$entryId));
        }else
        {
            $date = \date('c');
            $wpdb->insert($this->Loader->RECORDS_TABLE, array(
                'form_id' => $id,
                'original_id' => $originalEntryId,
                'date' => $date,
                'user_id' => \get_current_user_id(),
                'entry' => \json_encode($itemsToSave),
                'seq_num' => $seqId,
                'reference' => $reference,
                'raw' => \json_encode($raw)
            ));
        }

        $entryId=$wpdb->insert_id;




        $this->SaveDetails($entryId,$id,$entryItems);

        $seqId++;
        \update_option($seqKey,$seqId);
        return $wpdb->insert_id;
    }

    /**
     * @param $entryId
     * @param $entryItems EntryItemBase[]
     */
    public function SaveDetails($entryId,$formId,$entryItems){
        global $wpdb;
        for($i=0;$i<count($entryItems);$i++)
        {
            $details=$entryItems[$i]->GetDetails($entryId,$formId,$i);

            foreach($details as $currentDetail)
            {
                $wpdb->insert($this->Loader->RECORDS_DETAIL_TABLE,$currentDetail->ToObject());
            }
        }

    }

    /**
     * @param $entry EntryItemBase[]
     */
    public function GeneratePDF($entry)
    {

    }

    /**
     * @param $entryData
     * @param $fields FieldSettingsBase[]
     * @return EntryItemBase[]
     */
    public function InflateEntry($entryData,  $fields)
    {
        $entryItemList=array();
        foreach($entryData as $entryDataItem)
        {
            foreach($fields as $fieldItem)
            {
                if($fieldItem->Id==$entryDataItem->_fieldId)
                {
                    $entryItemList[]=$this->InflateEntryItem($fieldItem,$entryDataItem);
                }
            }

        }

        return $entryItemList;

    }



}