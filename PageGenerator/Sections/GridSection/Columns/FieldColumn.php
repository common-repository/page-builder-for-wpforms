<?php

namespace rnpagebuilder\PageGenerator\Sections\GridSection\Columns;

use Couchbase\User;
use rnpagebuilder\core\Managers\UserManager;
use rnpagebuilder\DTO\FieldCellTemplateOptionsDTO;
use rnpagebuilder\PageGenerator\Sections\GridSection\Columns\Core\GridColumnBase;
use rnpagebuilder\pr\Managers\InlineEditionManager\InlineEditionManager;
use Twig\Markup;

class FieldColumn extends GridColumnBase
{
    /** @var FieldCellTemplateOptionsDTO */
    public $Options;

    public function __construct($section, $options)
    {
        parent::__construct($section, $options);
        if($this->Options->IsSortable)
        {
            $this->Section->GetPageGenerator()->AddGlobalParameters('sortby');
            $this->Section->GetPageGenerator()->AddGlobalParameters('dir');
        }
    }


    public function Render()
    {
        $userManager=new UserManager($this->GetLoader());
        if($this->Options->Editable&&$userManager->CurrentUserHasRole($this->Options->AllowedRoles))
            return InlineEditionManager::RenderInlineEdition($this->Options->FieldId,$this->Options->PathId,$this->Section->GetEntryRetriever());
        return new Markup($this->Section->GetEntryRetriever()->GetCurrentRowHtmlValue($this->Options->FieldId,$this->Options->PathId),"UTF-8");
    }

    public function GetHeader()
    {
        return new Markup($this->Section->loader->GetTwigManager()->Render('PageGenerator/Sections/GridSection/Columns/FieldColumn.twig',$this),'UTF-8');
    }

    public function GetSortDirection(){
        if(!$this->Options->IsSortable)
            return 'Hidden';

        return 'None';
    }

    public function GetSortURL($asc=true)
    {
        return $this->Section->GetPageGenerator()->GetSortURL($this->Options->FieldId,$this->Options->PathId,$asc);
    }

    public function MaybeUpdateDataSource()
    {
        parent::MaybeUpdateDataSource();
        $sortBy=$this->Section->GetPageGenerator()->GetGetParameter('sortby');
        if($this->Options->IsSortable&&$sortBy!='')
        {
            $field=$this->Options->FieldId;
            if($this->Options->PathId!='')
                $field.='_'.$this->Options->PathId;

            if($sortBy!=$field)
                return;

            $dir='asc';
            if($this->Section->GetPageGenerator()->GetGetParameter('dir')!=''&&$this->Section->GetPageGenerator()->GetGetParameter('dir')=='desc')
                $dir='desc';

            $this->Section->GetPageGenerator()->AddSort($this->Options->FieldId,$this->Options->PathId,$dir=='asc');
        }
    }

    public function GetCaretColor($asc){
        $sort=$this->Section->GetPageGenerator()->GetUsedSort();
        if($sort==null||$sort->FieldId!=$this->Options->FieldId||$sort->PathId!=$this->Options->PathId)
            return '#dfdfdf';

        if($asc&&$sort->Orientation=='asc')
            return 'blue';

        if(!$asc&&$sort->Orientation=='desc')
            return 'blue';

        return '#dfdfdf';
    }



}