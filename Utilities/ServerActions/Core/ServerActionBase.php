<?php


namespace rnpagebuilder\Utilities\ServerActions\Core;


use rnpagebuilder\core\Loader;
use rnpagebuilder\DTO\core\StoreBase;

abstract class ServerActionBase extends StoreBase
{
    public $Name;
    /** @var Loader */
    public $Loader;

    public function ToJson(){
        $data= json_encode($this);
        $nonce=wp_create_nonce($data);

        return (object)[
            'Data'=>$data,
            'Nonce'=>$nonce
        ];
    }

    public  function Register($loader){
        $this->Loader=$loader;
    }


}