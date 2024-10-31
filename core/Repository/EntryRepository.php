<?php


namespace rnpagebuilder\core\Repository;


use rnpagebuilder\core\db\core\RepositoryBase;
use rnpagebuilder\core\Integration\Adapters\WPForm\Loader\WPFormSubLoader;

class EntryRepository extends RepositoryBase
{
    /** @var WPFormSubLoader */
    public $Loader;
    public function GetOriginalEntryId($entryId)
    {
        return $this->DBManager->GetVar('select original_id from '.$this->Loader->RECORDS_TABLE.' where id=%s',$entryId);
    }

    public function ApproveEntry($entryId)
    {
        return $this->DBManager->Update($this->Loader->GetRecordsTableName(),['starred'=>1],['entry_id'=>$entryId]);
    }

    public function DisapproveEntry($entryId)
    {
        return $this->DBManager->Update($this->Loader->GetRecordsTableName(),['starred'=>0],['entry_id'=>$entryId]);
    }

    public function DeleteEntry($entryId)
    {
        if(!$this->DBManager->Delete($this->Loader->GetRecordsTableName(),['entry_id'=>$entryId]))
            return false;
        return true;
    }

    public function GetEntryById($entryId){
        if($this->Loader->IsWPFormsPro())
            return wpforms()->entry->get( $entryId );
        return $this->DBManager->GetResult('select entry_id,fields,date,form_id from '.$this->Loader->GetRecordsTableName().' where entry_id=%d',$entryId);
    }

    public function UpdateEntry($entryId, $fields,$date)
    {
        if($this->Loader->IsWPFormsPro())
            wpforms()->entry->update( $entryId, ['fields'=>wp_json_encode($fields),'date_modified'=>$date], '');
        else
            $this->DBManager->Update($this->Loader->GetRecordsTableName(),[
                'fields'=>wp_json_encode($fields)
            ],['entry_id'=>$entryId]);

    }

}