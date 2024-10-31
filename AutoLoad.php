<?php

spl_autoload_register('rnpagebuilder');
function rnpagebuilder($className)
{
    if(strpos($className,'rnpagebuilder\\')!==false)
    {
        $NAME=basename(\dirname(__FILE__));
        $DIR=dirname(__FILE__);
        $path=substr($className,13);
        $path=str_replace('\\','/', $path);
        require_once $DIR.$path.'.php';
    }
}