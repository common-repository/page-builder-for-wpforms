<?php

namespace rnpagebuilder\PageGenerator\Managers\ActionManager;

use rnpagebuilder\core\Repository\EntryRepository;
use rnpagebuilder\PageGenerator\Managers\MessageManager;

class DeleteAction extends ActionBase
{

    public function GetActionId()
    {
        return 'delete';
    }

    public function Execute()
    {
        $ref=$this->GetRef();
        $repository=new EntryRepository($this->PageGenerator->Loader);
        if($repository->DeleteEntry($ref))

            $this->PageGenerator->Notifications[]=MessageManager::ShowSuccessMessage('The entry was deleted successfully');
        else
            $this->PageGenerator->Notifications[]=MessageManager::ShowErrorMessage('Sorry the entry could not be deleted, please try again');
        return false;
    }
}