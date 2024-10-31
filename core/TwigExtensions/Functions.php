<?php


namespace rnpagebuilder\core\TwigExtensions;


use rnpagebuilder\core\Loader;
use rnpagebuilder\DTO\IconOptionsDTO;
use Twig\Extension\AbstractExtension;
use Twig\Markup;
use Twig\TwigFunction;

class Functions extends AbstractExtension
{
    /** @var $Loader Loader */
    public $Loader;
    public function __construct($loader)
    {
        $this->Loader=$loader;
    }

    public function getFunctions()
    {
        $me=$this;
        return[
            new TwigFunction('ParseIcon',function ($iconName,$params='1.5em'){return $this->ParseIcon($iconName,$params);}),
            new TwigFunction('ParseURL',[$this,'ParseURL']),
            new TwigFunction('AddStyle',function ($handler,$url,$dependency=array())use($me){
                wp_enqueue_style($handler,$me->Loader->URL.$url,$dependency);
            }),
            new TwigFunction('ParseDisabled',function ($condition){
                if($condition==null)
                    return '';

                return 'disabled';
            }),
            new TwigFunction('ParseChecked',function ($condition){
                if($condition==null)
                    return '';

                return 'checked';
            }),
            new TwigFunction('ParseSelected',function ($condition){
                if($condition==null)
                    return '';

                return 'selected';
            }),
            new TwigFunction('ParseSubmitActionJavascript',function ($actionName,$params)
            {
                if(is_array($params)||is_object($params))
                    $params=json_encode($params);

                if($params=='event.target.value')
                    return "javascript:RNSubmitAction(event,'".esc_attr__($actionName)."',$params)";
                return "javascript:RNSubmitAction(event,'".esc_attr__($actionName)."',".json_encode($params).")";
            })
        ];
    }

    /**
     * @param $icon IconOptionsDTO
     * @return string|void
     */
    public function ParseIcon($icon,$size='1.5em'){
        if(is_array($icon))
            $icon=(Object)$icon;
        if($icon->Value=='')
            return '';

        $urlToUse=$this->Loader->URL.'pr/icons/fontawesome/'.esc_attr($icon->Source).'/'.esc_attr($icon->Value).'.svg';
        return new Markup('<span style="margin-right:3px;width: '.esc_attr($size).';height: '.esc_attr($size).';-webkit-mask-position: center ;-webkit-mask-repeat: no-repeat ;mask-position: center;mask-repeat: no-repeat;background-color:currentColor;display: inline-flex;mask-image: url('.$urlToUse.');-webkit-mask-image: url('.$urlToUse.')"></span>','UTF-8');
    }


}