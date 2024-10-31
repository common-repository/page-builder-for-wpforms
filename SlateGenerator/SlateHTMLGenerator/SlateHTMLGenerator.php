<?php


namespace rnpagebuilder\SlateGenerator\SlateHTMLGenerator;



use rnpagebuilder\core\Integration\Processors\Entry\Retriever\EntryRetrieverBase;
use rnpagebuilder\core\Loader;
use rnpagebuilder\core\Utils\ObjectSanitizer;
use rnpagebuilder\PageBuilderOld\DataSources\Core\DataSourceBase;
use rnpagebuilder\PageBuilderOld\DataSources\Core\DataSourceRow;
use rnpagebuilder\PageBuilderOld\Renderers\PageRenderer;
use rnpagebuilder\SlateGenerator\SlateTextGenerator\SlateTextGenerator;
use rnpagebuilder\Utilities\HtmlTagWrapper;
use rnpagebuilder\Utilities\PageUtilities;
use rnpagebuilder\Utilities\Sanitizer;
use rnpagebuilder\Utilities\ServerActions\GoToPage\GoToServerAction;
use Twig\Markup;

class SlateHTMLGenerator
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


    public function GetHTML($content,DataSourceRow $row=null)
    {
        $document=new HtmlTagWrapper('div');
        if(!isset($content->document)||!isset($content->document->nodes))
            return '';

        $text='';

        foreach($content->document->nodes as $paragraph)
        {
            if($paragraph->type=='paragraph')
            {
                $pelement=$document->CreateAndAppendChild('p');
                switch (Sanitizer::GetStringValueFromPath($paragraph,['data','className']))
                {
                    case 'aligncenter':
                        $pelement->AddStyle('text-align','center');
                        break;
                    case 'alignleft':
                        $pelement->AddStyle('text-align','left');
                        break;
                    case 'alignright':
                        $pelement->AddStyle('text-align','right');
                        break;
                }
                $this->ParseNode($paragraph, $row,$pelement);
            }

        }

        return new Markup($document->GetHTML(),'UTF-8');

    }

    /**
     * @param $node
     * @param DataSourceRow|null $row
     * @param $domNode HtmlTagWrapper
     */
    private function ParseNode($node, $row=null,$domNode)
    {
        $text='';
        foreach ($node->nodes as $node)
        {
            switch ($node->object)
            {
                case 'text':

                    $this->CreateSpanFromTextNode($node,$domNode);

                    break;
                case 'inline':
                    if($node->type=='link')
                    {
                        $linkInfo=ObjectSanitizer::Sanitize($node->data,(object)[
                            "ClickParams"=>(object)[],
                            "ClickTarget"=>'',
                            'ClickAction'=>'',
                            'LinkText'=>(object)[]
                        ]);

                        $textGenerator=new SlateTextGenerator($this->Loader,$this->PageRenderer);
                        $url='';
                        if($linkInfo->ClickAction=='OpenURL')
                        {
                            $url=(new SlateTextGenerator($this->Loader,$this->PageRenderer))->GetText($linkInfo->ClickParams,$row);
                        }else{
                            $pageUtilities=new PageUtilities($this->PageRenderer);
                            $goToPageAction=new GoToServerAction();
                            $goToPageAction->Merge($linkInfo->ClickParams);
                            if($goToPageAction->PageId!=0)
                            {
                                $goToPageAction->Initialize($row);
                                $url = $pageUtilities->CreateLink([$goToPageAction]);
                            }
                        }

                        $link=$domNode->CreateAndAppendChild('a');
                        if($linkInfo->ClickTarget=='_blank')
                            $link->SetAttribute('target','_blank');

                        $link->SetText($textGenerator->GetText($linkInfo->LinkText,$row));
                        $link->SetAttribute('href',$url);
                        break;
                    }


                    $span=$domNode->CreateAndAppendChild('span');
                    $this->CreateSpanFromFieldNode($node,$row,$domNode);
                    break;
            }
        }
    }

    /**
     * @param $node
     * @param $domNode HtmlTagWrapper
     */
    private function CreateSpanFromTextNode($node,$domNode)
    {
        if(!isset($node->leaves))
            return;

        foreach($node->leaves as $leaf)
        {
            $span=$domNode->CreateAndAppendChild('span');
            $span->SetText($leaf->text);
            $this->AddStyles($leaf,$span);
        }
    }

    /**
     * @param $node
     * @param DataSourceRow|null $row
     * @param $domNode HtmlTagWrapper
     */
    private function CreateSpanFromFieldNode($node,DataSourceRow $row=null,$domNode)
    {
        if(!$this->Loader->IsPR())
            return;
        $span=$domNode->CreateAndAppendChild('span');
        $span->SetText($this->Loader->PRLoader->GetFixedFieldValue($this->PageRenderer,$node,$row));
        $this->AddStyles($node,$span);

    }

    /**
     * @param $node
     * @param $domNode HtmlTagWrapper
     */
    private function AddStyles(  $node,$domNode)
    {
        if(!isset($node->marks))
            return;

        foreach($node->marks as $currentMark)
        {
            switch ($currentMark->type)
            {
                case 'color':
                    $domNode->AddStyle('color',Sanitizer::GetStringValueFromPath($currentMark,['data','hex'],'#000000'));
                    break;
                case 'bold':
                    $domNode->AddStyle('font-weight','bold');
                    break;
                case 'italic':
                    $domNode->AddStyle('font-style','italic');
                    break;
                case 'underlined':
                    $domNode->AddStyle('text-decoration','underline');
                    break;
                case 'size':
                    $size=Sanitizer::GetStringValueFromPath($currentMark,['data','size']);
                    if($size!='inherit')
                        $size.='px';

                    $domNode->AddStyle('font-size',$size);
                    break;
            }
        }

    }


}