<?php

namespace rnpagebuilder\core\Repository;

use rnpagebuilder\core\db\core\RepositoryBase;

class EntryMetaRepository extends RepositoryBase
{

    public function AddMeta($entryId,$name,$value)
    {
        $result=$this->GetMeta($entryId,$name);

        if($result==null)
            $this->DBManager->Insert($this->Loader->EntryMetaTable,[
                'entry_id'=>$entryId,
                'name'=>$name,
                'value'=>$value
            ]);
        else
            $this->DBManager->Update($this->Loader->EntryMetaTable,[
                'value'=>$value
            ],[
               'entry_id'=>$entryId,
               'name'=>$name
            ]);


    }

    public function GetMeta($entryId, $name,$defaultValue=null)
    {
        $value= $this->DBManager->GetVar('select value from '.$this->Loader->EntryMetaTable.' where entry_id=%d and name=%s', $entryId, $name);
        if($value==null)
            return $defaultValue;
        return $value;

    }


}