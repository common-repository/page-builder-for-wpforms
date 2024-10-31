<?php

namespace rnpagebuilder\PageGenerator\Managers\ActionManager;

use rnpagebuilder\core\Repository\EntryRepository;
use rnpagebuilder\PageGenerator\Managers\MessageManager;

class DisapproveAction extends ActionBase
{

    public function GetActionId()
    {
        return 'disapprove';
    }

    public function Execute()
    {
        $ref=$this->GetRef();
        $repository=new EntryRepository($this->PageGenerator->Loader);
        if($repository->DisapproveEntry($ref))

            $this->PageGenerator->Notifications[]=MessageManager::ShowSuccessMessage('The entry was disapproved successfully');
        else
            $this->PageGenerator->Notifications[]=MessageManager::ShowErrorMessage('Sorry the entry could not be disapproved, please try again');
        return false;
    }
}