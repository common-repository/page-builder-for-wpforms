<?php

namespace rnpagebuilder\PageGenerator\TextRenderer;


use rnpagebuilder\PageGenerator\TextRenderer\Core\TextRendererBase;

class TextTextRenderer extends TextRendererBase
{

    public $TagToUse;
    public $Styles;
    public $Text;
    public $Attrs=[];
    protected function GetTemplateName()
    {
        return 'PageGenerator/TextRenderer/TextTextRenderer.Twig';
    }

    protected function InternalInitialize()
    {
        parent::InternalInitialize();
        $this->Attrs=[];
        $this->TagToUse=$this->GetTag();
        $this->Styles='';
        $this->Text=$this->GetText();
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


                            $this->Attrs['href']=$pdfBuilderTemplates=RNPDFBuilder()->GetPDFURL($this->PageGenerator->EntryRetriever->GetCurrentEntryId(),$currentMark->attrs->href);
                            break;
                        case 'PDFImporter':

                            if(!function_exists('RNPDFImporter'))
                            {
                                return '';
                            }


                            $this->Attrs['href']=RNPDFImporter()->GetPDFURL($this->PageGenerator->EntryRetriever->GetCurrentEntryId(),$currentMark->attrs->href);
                            break;
                        case 'Export':
                            $this->Attrs['href']=$this->PageGenerator->GetExportURL();
                            $this->Attrs['target']='_blank';
                            break;
                    }




                    return 'a';


                }
            }
        return 'span';
    }

    public function GetText()
    {
        return $this->Content->text;
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