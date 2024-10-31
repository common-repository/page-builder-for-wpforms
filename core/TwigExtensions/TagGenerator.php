<?php


namespace rnpagebuilder\core\TwigExtensions;


class TagGenerator
{
    public $TagName;
    public $Params=[];

    public function __construct($tagName,$params=[])
    {
        $this->TagName=$tagName;
        $this->Params=$params;
    }


    public static function Generate($tagName,$params)
    {
        $generator=new TagGenerator($tagName,$params);
        return $generator->StartTag();
    }

    public function StartTag($keepOpen=false){
        $text='<'.$this->TagName;
        $class=[];
        if(isset($this->Params['class']))
        {
            $class = $this->Params['class'];
            unset($this->Params['class']);
        }

        $styles=[];
        if(isset($this->Params['style']))
        {
            $styles=$this->Params['style'];
            unset($this->Params['style']);
        }

        if(count($this->Params)>0)
        {
            $text.=' ';
            foreach ($this->Params as $attributeName => $value)
            {
                $text .= $attributeName . '="' . esc_attr($value) . '"';
            }

        }
        if(count($styles)>0)
        {
            $text.=' style="';
            foreach ($styles as $styleName=>$styleValue)
            {
                $text.=$styleName.':'.esc_attr__($styleValue).';';
            }
            $text.='"';
        }

        if(count($class)>0)
            $text.=' class="'.$class.'"';


        if(!$keepOpen)
            $text.='/>';
        else
            $text.='>';

        return $text;


    }

    public function EndTag(){
        return '</'.$this->TagName.'>';
    }


}