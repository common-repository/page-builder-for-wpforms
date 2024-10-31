<?php


namespace rnpagebuilder\core\db;


use rnpagebuilder\core\db\core\RepositoryBase;

class FormRepository extends RepositoryBase
{
    public function GetFieldConfig($formId)
    {
        $result= $this->DBManager->GetVar('select fields from '.$this->Loader->FormConfigTable.' where original_id=%s',$formId);
        if($result==null)
            return null;

        $fields= json_decode($result);
        return $this->InflateFieldSettings($fields);

    }

    private function InflateFieldSettings($fields)
    {
        $fieldSettings=array();
        foreach($fields as $currentField)
        {
            $retriever=$this->Loader->CreateEntryRetriever();
            $factory=$retriever->GetFieldSettingsFactory();
            $fieldSettings[]=$factory->GetFieldByOptions($currentField);
        }

        return $fieldSettings;
    }

    public function GetOriginalId($formId)
    {
        global $wpdb;
        $id= $wpdb->get_var($wpdb->prepare('select original_id from '.$this->Loader->FormConfigTable.' where id=%s',$formId));
        return $id;
    }

}