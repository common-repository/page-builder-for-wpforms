<?php


namespace rnpagebuilder\core\Integration\Processors\Entry\EntryItems;


use rnpagebuilder\core\Integration\Processors\Entry\EntryItems\Core\EntryItemBase;

class FileUploadFile{
    public $URL;
    public $Name;
    public $Extension;
    public $Mime;

    /**
     * FileUploadFile constructor.
     */
    public function __construct($url,$name='',$extension='',$mime='')
    {
        $this->URL=$url;
        $this->Name=$name;
        $this->Extension=$extension;
        $this->Mime=$mime;
    }


}

