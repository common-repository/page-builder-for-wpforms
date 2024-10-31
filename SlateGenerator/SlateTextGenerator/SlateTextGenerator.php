<?php


namespace rnpagebuilder\SlateGenerator\SlateTextGenerator;



use rnpagebuilder\core\Integration\Processors\Entry\Retriever\EntryRetrieverBase;
use rnpagebuilder\core\Loader;
use rnpagebuilder\PageBuilderOld\DataSources\Core\DataSourceBase;
use rnpagebuilder\PageBuilderOld\DataSources\Core\DataSourceRow;
use rnpagebuilder\PageBuilderOld\Renderers\PageRenderer;
use Twig\Markup;

class SlateTextGenerator
{

    /** @var PageRenderer */
    public $PageRenderer;
    /** @var Loader */
    public $Loader;

    public function __construct($loader,$pageRenderer)
    {
        $this->Loader=$loader;
        $this->PageRenderer=$pageRenderer;
    }


    public function GetText($content,DataSourceRow $row=null)
    {
        if(!isset($content->document)||!isset($content->document->nodes))
            return '';

        $text='';

        foreach($content->document->nodes as $paragraph)
        {
            if($paragraph->type=='paragraph')
            {
                $text=$this->ParseNode($paragraph, $row);
            }

        }

        return new Markup($text,'UTF-8');

    }

    private function ParseNode($node,DataSourceRow $row=null)
    {
        $text='';
        foreach ($node->nodes as $node)
        {
            switch ($node->object)
            {
                case 'text':
                    $text .= $this->GetValueFromTextNode($node);
                    break;
                case 'inline':
                    $text .= $this->GetValueFromFieldNode($node,$row);
                    break;
            }
        }

        return $text;
    }

    private function GetValueFromTextNode($node)
    {
        if(!isset($node->leaves))
            return '';

        $text='';
        foreach($node->leaves as $leaf)
        {
            $text.=esc_html($leaf->text);
        }

        return $text;
    }

    private function GetValueFromFieldNode($node,DataSourceRow $row=null)
    {
        if(!$this->Loader->IsPR())
            return 'Not available in free version';

        return $this->Loader->PRLoader->GetFixedFieldValue($this->PageRenderer,$node,$row);


    }


}