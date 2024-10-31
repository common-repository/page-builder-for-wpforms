<?php


namespace rnpagebuilder\core\Integration\Processors\Entry\EntryItems;


use rnpagebuilder\core\Integration\Processors\Entry\EntryItems\Core\EntryItemBase;
use rnpagebuilder\core\Utils\ArrayUtils;

class FileUploadEntryItem extends EntryItemBase
{
    /** @var FileUploadFile[] */
    public $Files;

    public function __construct()
    {
        parent::__construct();
        $this->Files=[];
    }

    public function AddFile($url='',$name='',$extension='',$mime='')
    {
        $this->Files[]=new FileUploadFile($url,$name,$extension,$mime);
    }

    public function CreateAndAddFile()
    {
        $file=new FileUploadFile('');
        $this->Files[]=$file;
        return $file;
    }
    public function GetText()
    {
        $fileNames=ArrayUtils::Map($this->Files,function ($item){return $item->URL;});
        return implode(', ',$fileNames);
    }


    protected function InternalGetObjectToSave()
    {
        return (object)array(
            'Value'=>$this->Files
        );
    }



    public function InitializeWithOptions($field,$options)
    {
        $this->Field=$field;
        if(isset($options->Path))
            $this->Path=$options->Path;

        if(isset($options->FileId))
            $this->FileId=$options->FileId;

        if(isset($options->URL))
            $this->URL=$options->URL;
    }

    public function GetHtml($style = 'standard')
    {
        // TODO: Implement GetHtml() method.
    }

    public function GetType()
    {
        return 'fileupload';
    }

    public function IsEmpty()
    {
        return count($this->Files)==0;
    }

    public function InternalGetDetails($base)
    {
        $itemList=array();
        foreach($this->Files as $currentFile)
        {
            $clone=$base->CloneItem();
            $clone->Value=$currentFile->URL;
            $clone->ExValue2=$currentFile->Extension;
            $clone->ExValue3=$currentFile->Mime;
            $itemList[]=$clone;

        }
        return $itemList;
    }


}

