<?php


namespace rnpagebuilder\Utilities\ServerActions\Core;


use rnpagebuilder\core\Exception\FriendlyException;
use rnpagebuilder\core\Utils\ObjectSanitizer;

class ServerActionsManager
{
    public function MaybeRegisterFromURL($loader)
    {
        if(isset($_GET['PageActions']))
        {
            $actions=base64_decode(strval($_GET['PageActions']));
            $actions=ObjectSanitizer::Sanitize(json_decode($actions),[(object)['Nonce'=>'','Data'=>'']]);
            if($actions==false)
                return;

            foreach($actions as $currentAction)
            {
                if(!wp_verify_nonce($currentAction->Nonce,$currentAction->Data))
                    throw new FriendlyException('Invalid action, please try again');

                $currentAction=ObjectSanitizer::Sanitize(json_decode($currentAction->Data),(Object)["PageId"=>0,"Name"=>""]);
                /** @var ServerActionBase $serverAction */
                $serverAction=ServerActionFactory::GetServerAction($currentAction);
                $serverAction->Register($loader);
            }
        }
    }
}