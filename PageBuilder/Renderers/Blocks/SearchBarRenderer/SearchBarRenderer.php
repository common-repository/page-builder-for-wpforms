<?php


namespace rnpagebuilder\PageBuilder\Renderers\Blocks\SearchBarRenderer;


use rnpagebuilder\core\Exception\FriendlyException;
use rnpagebuilder\DTO\ComparisonTypeEnumDTO;
use rnpagebuilder\DTO\FieldTypeEnumDTO;
use rnpagebuilder\DTO\FormFieldBlockOptionsDTO;
use rnpagebuilder\DTO\RNBlockBaseOptionsDTO;
use rnpagebuilder\DTO\RunnableSearchBarOptionsDTO;
use rnpagebuilder\DTO\RunnableSearchDateOptionsDTO;
use rnpagebuilder\DTO\RunnableSearchFieldBaseOptionsDTO;
use rnpagebuilder\DTO\RunnableSearchFieldDTO;
use rnpagebuilder\DTO\RunnableSearchMultipleOptionsDTO;
use rnpagebuilder\DTO\RunnableSearchTextOptionsDTO;
use rnpagebuilder\DTO\RunnableSearchUserOptionsDTO;
use rnpagebuilder\DTO\SearchBarBlockOptionsDTO;
use rnpagebuilder\PageBuilder\Renderers\Blocks\Core\BlockRendererBase;
use rnpagebuilder\PageBuilder\Renderers\ColumnRenderer;
use rnpagebuilder\PageBuilder\Renderers\Core\RendererBase;
use Twig\Markup;

class SearchBarRenderer extends BlockRendererBase
{
    /** @var SearchBarBlockOptionsDTO */
    public $Options;
    /** @var SearchFieldRenderer[] */
    public $Fields;
    public $PostItems;
    public function __construct(ColumnRenderer $columnRenderer, SearchBarBlockOptionsDTO $options,$dataSource)
    {
        parent::__construct($columnRenderer, $options,$dataSource);

        $this->PostItems=$this->GetPageRenderer()->PageGenerator->GetFieldPostItem($this);
        $this->AddScript('SearchBar','js/dist/RNMainRunnableSearchBar_bundle.js');
        $this->AddStyle('Button','Styles/Buttons.css');
        $this->Fields=[];
        foreach($options->Fields as $currentField)
        {
            $ds= $this->GetPageRenderer()->GetDefaultDataSource();

            if($currentField->FieldId!=''&&$currentField->ComparisonType!='')
                $this->Fields[] = new SearchFieldRenderer($this->loader, $this->twig, $this, $currentField);
        }
    }


    public function HasRuntimeOptions()
    {
        return true;
    }

    protected function InternalGetOptions()
    {
        $options=(new RunnableSearchBarOptionsDTO())->Merge();
        foreach($this->Options->Fields as $CurrentField)
        {
            $field=$this->GetPageRenderer()->GetFieldById($CurrentField->FieldId);
            $currentOptions=null;
            switch ($field->Type)
            {
                case FieldTypeEnumDTO::$List:
                case FieldTypeEnumDTO::$Text:
                    $currentOptions=new RunnableSearchTextOptionsDTO();
                    $currentOptions->SearchType='Text';
                    $this->AddScript('SearchText','js/dist/RNMainRunnableSearchText_bundle.js',['@SearchBar']);
                    break;
                case FieldTypeEnumDTO::$Multiple:
                    $currentOptions=new RunnableSearchMultipleOptionsDTO();
                    $currentOptions->SearchType='Multiple';
                    $this->AddScript('rnselect','js/lib/tomselect/js/tom-select.complete.js');
                    $this->AddStyle('rnselect','js/lib/tomselect/css/tom-select.bootstrap5.css');

                    $this->AddScript('SearchMultiple','js/dist/RNMainRunnableSearchMultiple_bundle.js',['@SearchBar','@rnselect']);
                    break;
                case FieldTypeEnumDTO::$Date:
                case FieldTypeEnumDTO::$DateTime:
                    $currentOptions=new RunnableSearchDateOptionsDTO();
                    $currentOptions->Merge();
                    $currentOptions->Format='m-d-y';
                    $currentOptions->SearchType='Date';
                    $this->AddScript('rndate','js/lib/date/flatpickr.js');
                    $this->AddStyle('rndate','js/lib/date/flatpickr.min.css');

                    $this->AddScript('SearchDate','js/dist/RNMainRunnableSearchDate_bundle.js',['@SearchBar','@rndate']);
                    break;
                case FieldTypeEnumDTO::$Time:
                    $currentOptions=new RunnableSearchFieldBaseOptionsDTO();
                    $currentOptions->Merge();
                    $currentOptions->SearchType='Time';
                    $this->AddScript('rndate','js/lib/date/flatpickr.js');
                    $this->AddStyle('rndate','js/lib/date/flatpickr.min.css');

                    $this->AddScript('SearchDate','js/dist/RNMainRunnableSearchTime_bundle.js',['@SearchBar','@rndate']);
                    break;

                case FieldTypeEnumDTO::$User:
                    $currentOptions=new RunnableSearchUserOptionsDTO();
                    $currentOptions->Merge();
                    $currentOptions->SearchType='User';

                    if($CurrentField->ComparisonType==ComparisonTypeEnumDTO::$UserIs||$CurrentField->ComparisonType==ComparisonTypeEnumDTO::$UserIsNot)
                    {
                        $currentOptions->UserSearchType='User';
                        $userNonce=$this->GetPageRenderer()->PageGenerator->GetPostItem('SearchUserNonce');
                        if($userNonce==null)
                        {
                            $this->GetPageRenderer()->PageGenerator->AddPostItem('SearchUserNonce',wp_create_nonce('pb_user_search'));
                        }
                    }

                    if($CurrentField->ComparisonType==ComparisonTypeEnumDTO::$HasRole||$CurrentField->ComparisonType==ComparisonTypeEnumDTO::$HasNotRole)
                    {
                        $currentOptions->UserSearchType='Role';
                        $userNonce=$this->GetPageRenderer()->PageGenerator->GetPostItem('SearchRoleNonce');
                        if($userNonce==null)
                        {
                            $this->GetPageRenderer()->PageGenerator->AddPostItem('SearchRoleNonce',wp_create_nonce('pb_role_search'));
                        }
                    }


                    $this->AddScript('rnselect','js/lib/tomselect/js/tom-select.complete.js');
                    $this->AddStyle('rnselect','js/lib/tomselect/css/tom-select.bootstrap5.css');
                    $this->AddScript('SearchUser','js/dist/RNMainRunnableSearchUser_bundle.js',['@SearchBar','@rnselect']);


                    break;
            }

            if($currentOptions==null)
                throw new FriendlyException('Search field for type '.$field->Type.' not found');

            $currentOptions->Id=$CurrentField->Id;
            $options->Fields[]=$currentOptions;
        }

        return $options;
    }

    public function MaybeUpdateDataSource()
    {
        foreach($this->Fields as $currentField)
            $currentField->MaybeUpdateDataSource();
    }


    protected function GetTemplateName()
    {
        return 'Blocks/SearchBarRenderer/SearchBarRenderer.twig';
    }

    public function GetLabel(){

    }

}

