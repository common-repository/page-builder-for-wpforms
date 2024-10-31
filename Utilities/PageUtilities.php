<?php


namespace rnpagebuilder\Utilities;


use rnpagebuilder\PageBuilderOld\DataSources\FormDataSource\FormRow;
use rnpagebuilder\PageBuilderOld\Renderers\PageRenderer;
use rnpagebuilder\Utilities\ServerActions\Core\ServerActionBase;

class PageUtilities
{
    /** @var PageRenderer */
    public $PageRenderer;
    public function __construct($pageRenderer)
    {
        $this->PageRenderer=$pageRenderer;
    }

    /**
     * @param $pageActions ServerActionBase[]
     * @return string
     * @throws \rnpagebuilder\core\Exception\FriendlyException
     */
    public function CreateLink($pageActions)
    {

        $actions=[];
        foreach($pageActions as $currentPageAction)
        {
            $actions[]=$currentPageAction->ToJson();
        }

        $data=base64_encode(json_encode($actions));


        return '?PageActions='.$data;
    }


}