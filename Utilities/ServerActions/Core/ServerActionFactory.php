<?php


namespace rnpagebuilder\Utilities\ServerActions\Core;


use rnpagebuilder\core\Exception\FriendlyException;
use rnpagebuilder\Utilities\ServerActions\GoToPage\GoToServerAction;

class ServerActionFactory
{
    /**
     * @param $action ServerActionBase
     */
    public static function GetServerAction($action)
    {
        switch ($action->Name)
        {
            case 'GoToPage':
                return (new GoToServerAction())->Merge($action);
            default:
                throw new FriendlyException('Invalid action '.$action->Name);
        }
    }
}