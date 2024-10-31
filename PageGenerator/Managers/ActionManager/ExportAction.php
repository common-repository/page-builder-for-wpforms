<?php

namespace rnpagebuilder\PageGenerator\Managers\ActionManager;

use rnpagebuilder\core\Repository\EntryRepository;
use rnpagebuilder\PageGenerator\Managers\MessageManager;

class ExportAction extends ActionBase
{

    public function GetActionId()
    {
        return 'export';
    }

    public function Execute()
    {
        $repository=new EntryRepository($this->PageGenerator->Loader);
        $this->PageGenerator->MaybeUpdateDataSource();
        $this->PageGenerator->EntryRetriever->ExecuteQuery(0,0);

        $rowManager=$this->PageGenerator->EntryRetriever->RowManager;

        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . str_replace('"', '\"', 'Export')  . '.csv";');
        header('Content-Transfer-Encoding: binary');

        $fh = fopen('php://output', 'w');
        $csvRow=[];
        foreach($rowManager->EntryRetriever->FieldSettings as $currentField)
        {
            $csvRow[]=$currentField->Label;
        }


        fputcsv($fh,$csvRow);


        while ($rowManager->RowExist())
        {
            $csvRow=[];
            foreach($rowManager->EntryRetriever->FieldSettings as $currentField)
            {
                $csvRow[]=$rowManager->GetCurrentRowStringValue($currentField->Id,'Value','');
            }
            fputcsv($fh,$csvRow);
            $rowManager->GoNext();
        }
        die();
    }
}