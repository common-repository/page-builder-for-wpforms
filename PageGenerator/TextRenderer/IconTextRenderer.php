<?php

namespace rnpagebuilder\PageGenerator\TextRenderer;


use rnpagebuilder\DTO\IconOptionsDTO;
use rnpagebuilder\PageGenerator\Core\PageGenerator;
use rnpagebuilder\PageGenerator\TextRenderer\Core\TextRendererBase;

class IconTextRenderer extends TextRendererBase
{


    public $Styles;
    public $Value;
    public $Source;
    public $TagToUse;
    public $Attrs=[];

    protected function GetTemplateName()
    {
        return 'PageGenerator/TextRenderer/IconTextRenderer.Twig';
    }

    protected function InternalInitialize()
    {
        parent::InternalInitialize();
        $this->Source=$this->Content->attrs->source;
        $this->Value=$this->Content->attrs->value;
        $this->TagToUse=$this->GetTag();
        if(isset($this->Content->marks))
            foreach($this->Content->marks as $currentMark)
            {
                switch ($currentMark->type)
                {
                    case 'color':
                        $this->Styles.='color:'.$currentMark->attrs->color.'; ';
                        break;
                    case 'strong':
                        $this->Styles.='font-weight:bold; ';
                        break;
                    case 'em':
                        $this->Styles.='font-style:italic; ';
                        break;
                }
            }

        $this->Styles.='vertical-align:middle;display:inline-flex;';

    }

    public function GetTag()
    {
        if(isset($this->Content->marks))
            foreach($this->Content->marks as $currentMark)
            {
                if($currentMark->type=='link')
                {
                    $this->TagToUse='a';
                    $this->Attrs['target']=$currentMark->attrs->target;

                    switch ($currentMark->attrs->type)
                    {
                        case 'DeleteEntry':
                            $this->Attrs['href']=$this->PageGenerator->GetDeleteEntryURL();
                            break;
                        case 'ApproveEntry':
                            $this->Attrs['href']=$this->PageGenerator->GetApproveEntryURL();
                            break;
                        case 'DisapproveEntry':
                            $this->Attrs['href']=$this->PageGenerator->GetDisapproveEntryURL();
                            break;
                        case 'ViewEntry':
                            $this->Attrs['href']=$this->PageGenerator->GetViewEntryURL();
                            break;
                        case 'EditEntry':
                            $this->Attrs['href']=$this->PageGenerator->GetEditEntryURL();
                            break;
                        case 'CustomUrl':
                            $this->Attrs['href']=$currentMark->attrs->href;
                            break;
                        case 'MainPage':
                            $this->Attrs['href']=$this->PageGenerator->GetMainPageURL();
                            break;
                        case 'PDFBuilder':

                            if(!function_exists('RNPDFBuilder'))
                            {
                                return '';
                            }


                            $this->Attrs['href']=RNPDFBuilder()->GetPDFURL($this->PageGenerator->EntryRetriever->GetCurrentEntryId(),$currentMark->attrs->href);
                            break;
                        case 'PDFImporter':

                            if(!function_exists('RNPDFImporter'))
                            {
                                return '';
                            }


                            $this->Attrs['href']=RNPDFImporter()->GetPDFURL($this->PageGenerator->EntryRetriever->GetCurrentEntryId(),$currentMark->attrs->href);
                            break;
                    }




                    return 'a';


                }
            }
        return 'span';
    }

    public function GetIcon()
    {
        return (new IconOptionsDTO())->Merge([
            "Value"=>$this->Value,
            "Source"=>$this->Source,
            "Type"=>'Icon'
        ]);
    }

    public function GetAttributes()
    {
        $attrs='';
        foreach($this->Attrs as $name=>$value)
        {
            $attrs.=$name.'="'.esc_attr($value).'" ';
        }

        return $attrs;
    }






}