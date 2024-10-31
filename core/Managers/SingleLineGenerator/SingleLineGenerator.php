<?php

namespace rnpagebuilder\core\Managers\SingleLineGenerator;



use rnpagebuilder\core\Utils\ObjectSanitizer;
use rnpagebuilder\PageGenerator\Core\PageGenerator;

class SingleLineGenerator
{
    /** @var PageGenerator */
    public $PageGenerator;
    public $Options;
    /** @var \Closure */
    public $TagParser=null;
    public function __construct($generator)
    {
        $this->PageGenerator=$generator;
        require_once $this->PageGenerator->Loader->DIR.'vendor/autoload.php';
    }

    public function GetText($content){
        $content=ObjectSanitizer::Sanitize($content,["content"=>[(object)[
            "content"=>(object)[
                "type"=>''
            ]
        ]]]);

        if($content==null)
            return '';

        if(is_string($content))
            return $content;

        $text='';
        foreach($content->content as $currentItem)
        {
            if($this->TagParser!=null){
                $content=($this->TagParser)($currentItem);
                if($content!=null)
                {
                    $text .= $content;
                    continue;
                }


            }
            switch ($currentItem->type)
            {
                case 'text':
                    $text.=$currentItem->text;
                    break;
                case 'field':
                    $obj=ObjectSanitizer::Sanitize($currentItem,(object)['attrs'=>(object)["Type"=>'',"Value"=>""]]);
                    if($obj->attrs->Type=='Field')
                    {
                        $fieldId=$obj->attrs->Value;
                        $fieldPath='';
                        if(strpos($fieldId,'_'))
                        {
                            $fields=explode('_',$fieldId);
                            if(count($fields)!=2)
                                break;
                            $fieldId=$fields[0];
                            $fieldPath=$fields[1];
                        }
                        $text.=$this->PageGenerator->EntryRetriever->GetCurrentRowStringValue($fieldId,$fieldPath);
                    }

            }
        }

        return $text;
    }

    public function SetTagParser($param)
    {
        $this->TagParser=$param;
    }

}