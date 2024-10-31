<?php


namespace rnpagebuilder\HtmlGenerator;


use rnpagebuilder\PageBuilderOld\DataSources\Core\DataSourceRow;
use rnpagebuilder\PageBuilderOld\Renderers\PageRenderer;
use rnpagebuilder\SlateGenerator\SlateTextGenerator\SlateTextGenerator;
use Twig\Markup;

class HtmlGenerator
{
    /** @var PageRenderer */
    public $PageRenderer;
    public function __construct($loader,$pageRenderer)
    {
        $this->Loader=$loader;
        $this->PageRenderer=$pageRenderer;
    }

    public function GetText($content,DataSourceRow $row=null)
    {
        $matches=null;
        preg_match_all('/@@RNFIELD(.*?(?=RNFIELD\$\$))RNFIELD\$\$/',$content,$matches,PREG_SET_ORDER);

        if($row==null)
            $row=$this->PageRenderer->GetCurrentRow();

        foreach($matches as $currentMatch)
        {
            if(count($currentMatch)==2)
            {
                $text='';
                $options=json_decode($currentMatch[1]);
                if($options!=false&&$row!=null)
                {
                    $text=$row->GetStringValue($options->FieldId);
                }

                $content=str_replace($currentMatch[0],esc_html($text),$content);

            }



        }
        return new Markup($content,"UTF-8");
    }
}