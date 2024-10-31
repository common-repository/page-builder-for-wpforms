<?php

namespace rnpagebuilder\PageGenerator\TextRenderer\Core;

use rnpagebuilder\core\Loader;
use rnpagebuilder\PageGenerator\Core\PageGenerator;

interface ITextRendererParent
{
    /**
     * @return Loader
     */
    function GetLoader();

}