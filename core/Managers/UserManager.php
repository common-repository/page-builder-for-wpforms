<?php


namespace rnpagebuilder\core\Managers;


use rnpagebuilder\core\Loader;

class UserManager
{
    /** @var Loader */
    public $Loader;
    public function __construct($loader)
    {
        $this->Loader=$loader;
    }

    public function UserCanViewPreview(){
        return current_user_can('administrator');
    }

    public function CurrentUserHasRole($roles)
    {

        $hasAccess=count($roles)==0;
        foreach($roles as $currentRole)
        {
            if(current_user_can($currentRole))
            {
                $hasAccess=true;
                break;
            }
        }

        return $hasAccess;
    }

}