<?php

namespace rnpagebuilder\PageGenerator\Managers\ActionManager;

use rnpagebuilder\PageGenerator\Core\PageGenerator;

class ActionManager
{
    /**
     * @param $pageGenerator PageGenerator
     * @return ActionBase
     */
    public static function GetAction($pageGenerator)
    {
        switch ($pageGenerator->GetGetParameter('rnaction'))
        {
            case 'delete':
                return new DeleteAction($pageGenerator);
            case 'approve':
                return new ApproveAction($pageGenerator);
            case 'disapprove':
                return new DisapproveAction($pageGenerator);
            case 'export':
                return new ExportAction($pageGenerator);
            default:
                return null;
        }
    }
}