<?php


namespace rnpagebuilder\core\Exception;


use Throwable;

class FriendlyException extends \Exception
{
    public $FriendlyError;
    public $TechnicalError;
    public $Severity;
    public $Details;

    public function __construct($friendlyError, $details='',$severity=0, $technicalError = "", Throwable $previous = null)
    {
        $this->Severity=$severity;
        $this->FriendlyError=$friendlyError;
        $this->TechnicalError=$technicalError;

        if($details=='')
            $this->Details=$friendlyError;
        else
            $this->Details=$details;

        parent::__construct($this->Details);


    }



    public function GetFriendlyException(){
        return $this->FriendlyError;
    }

}