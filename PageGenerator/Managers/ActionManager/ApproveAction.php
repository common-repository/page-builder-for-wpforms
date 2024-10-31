<?php

namespace rnpagebuilder\PageGenerator\Managers\ActionManager;

use rnpagebuilder\core\Repository\EntryRepository;
use rnpagebuilder\PageGenerator\Managers\MessageManager;

class ApproveAction extends ActionBase
{

    public function GetActionId()
    {
        return 'approve';
    }

    public function Execute()
    {
        $ref=$this->GetRef();
        $repository=new EntryRepository($this->PageGenerator->Loader);
        if($repository->ApproveEntry($ref))

            $this->PageGenerator->Notifications[]=MessageManager::ShowSuccessMessage('The entry was approved successfully');
        else
            $this->PageGenerator->Notifications[]=MessageManager::ShowErrorMessage('Sorry the entry could not be approved, please try again');
        return false;
    }

}