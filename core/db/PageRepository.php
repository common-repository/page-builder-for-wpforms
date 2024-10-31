<?php


namespace rnpagebuilder\core\db;


use rnpagebuilder\core\db\core\DBManager;
use rnpagebuilder\core\db\core\RepositoryBase;
use rnpagebuilder\core\Exception\FriendlyException;
use rnpagebuilder\core\Utils\ArrayUtils;
use rnpagebuilder\DTO\core\Factories\PageBuilderFactory;
use rnpagebuilder\DTO\PageBuilderBaseOptionsDTO;
use rnpagebuilder\DTO\PageBuilderOptionsDTO;
use rnpagebuilder\DTO\RNPageOptionsDTO;

class PageRepository extends RepositoryBase
{


    /**
     * @param PageBuilderBaseOptionsDTO $options
     */
    public function SavePage($options,$findNotUsedName=false)
    {
        $dbmanager=new DBManager(true);

        if($options->Id==0)
        {

            if(!$this->Loader->IsPR())
            {
                $count=$dbmanager->GetVar('select count(*) from '.$this->Loader->PageTable);
                if($count>=6)
                {
                    throw new FriendlyException('Sorry, the free version can create only one template');
                }
            }

            if($options->Name=='')
                throw new FriendlyException('Name can not be empty');
            $count=$dbmanager->GetVar('select count(*) from '.$this->Loader->PageTable.' where name =%s',trim($options->Name));
            if($count==1)
            {
                if($findNotUsedName)
                {
                    $isUnique=false;
                    $count=1;
                    $originalName=$options->Name;
                    while(!$isUnique){
                        $count++;
                        $options->Name=$originalName.' '.$count;
                        $result=$this->DBManager->GetResults('select id from '.$this->Loader->PageTable.' where name=%s',trim($options->Name));
                        $isUnique=count($result)==0;
                    }

                }else
                    throw new FriendlyException('The name ' . $options->Name . ' is already in use ');
            }


            return $dbmanager->Insert($this->Loader->PageTable,array(
                'name'=>trim($options->Name),
                'areas'=>json_encode($options->Areas),
                'formid'=>$options->FormId,
                'formulas'=>json_encode($options->Formulas),
                'type'=>$options->Type,
                'sort'=>json_encode($options->Sort),
                'general_settings'=>json_encode($options->GeneralSettings),
                'filter'=>json_encode($options->Filter)
            ));
        }else{
            $ids=$dbmanager->GetResults('select id from '.$this->Loader->PageTable.' where name =%s',trim($options->Name));
            if(count($ids)>0&&!ArrayUtils::Some($ids,function ($item)use($options){return $item->id==$options->Id;}))
                throw new FriendlyException('The name '.$options->Name.' is already in use by another template ');


            $dbmanager->Update($this->Loader->PageTable,array(
                'name'=>trim($options->Name),
                'formulas'=>json_encode($options->Formulas),
                'areas'=>json_encode($options->Areas),
                'formid'=>$options->FormId,
                'type'=>$options->Type,
                'sort'=>json_encode($options->Sort),
                'general_settings'=>json_encode($options->GeneralSettings),
                'filter'=>json_encode($options->Filter)
            ),array('id'=>$options->Id));

            return $options->Id;
        }
    }

    /**
     * @param $pageId
     * @return PageBuilderBaseOptionsDTO
     */

    public function GetPageById($pageId){
       $page= $this->DBManager->GetResult('select formulas Formulas,id Id,name Name,areas Areas,type Type,formid FormId,filter Filter,sort Sort,general_settings GeneralSettings from '.$this->Loader->PageTable.' where id=%d',$pageId);

       if($page==null)
           return null;

        $options=new \stdClass();

        $options->Name=$page->Name;
        $options->Areas=json_decode($page->Areas);
        $options->Id=$page->Id;
        $options->Type=$page->Type;
        $options->FormId=$page->FormId;
        $options->Filter=json_decode($page->Filter);
        $options->Sort=json_decode($page->Sort);
        $options->Formulas=json_decode($page->Formulas);
        $options->GeneralSettings=null;

        if($page->GeneralSettings!=null)
            $options->GeneralSettings=json_decode($page->GeneralSettings);

        return PageBuilderFactory::GetPageOptions($options);
    }
    public function GetPageList($length=30,$pageIndex=0,$searchTerm='')
    {
        $length=intval($length);
        $pageIndex=intval($pageIndex);

        $where='';
        global $wpdb;
        if($searchTerm!='')
            $where='where id='.$wpdb->prepare('%s',$searchTerm).' or name like "%'.$wpdb->esc_like($searchTerm).'%"';

        $result= $this->DBManager->GetResults('select id Id,type Type,name Name,formid FormId from '.$this->Loader->PageTable .' '.$where.'   limit '.($length*$pageIndex).', '.$length);
        $pages=array();
        $formsToQuery=[];
        global $wpdb;
        foreach($result as $currentItem)
        {
            $currentPage=new \stdClass();
            $pages[]=$currentPage;
            $currentPage->Name=$currentItem->Name;
            $currentPage->Id=$currentItem->Id;
            $currentPage->FormId=$currentItem->FormId;
            $currentPage->Type=$currentItem->Type;
            $formsToQuery[]=$currentItem->FormId;
        }

        $forms=[];
        if(count($formsToQuery)>0)
        {
            $forms = $this->DBManager->GetResults('select original_id Id, name Name from ' . $this->Loader->FormConfigTable . ' where original_id in(' . implode(',', $formsToQuery) . ')');
        }

        return array(
            'Pages'=>$pages,
            'Forms'=>$forms
        );

    }

    public function Delete($id)
    {
        $dbmanager=new DBManager(true);
        $dbmanager->Delete($this->Loader->PageTable,array('id'=>$id));

    }

    public function GetPageListCount($searchTerm='')
    {
        $where='';
        global $wpdb;
        if($searchTerm!='')
            $where='where id='.$wpdb->prepare('%s',$searchTerm).' or name like "%'.$wpdb->esc_like($searchTerm).'%"';
        return $this->DBManager->GetVar('select count(*) from '.$this->Loader->PageTable.' '.$where);
    }

    public function GetGeneralSettings($pageId)
    {
        $result= $this->DBManager->GetResult('select id Id, general_settings GeneralSettings,type Type from '.$this->Loader->PageTable.' where id=%d',$pageId);
        if($result==null)
            return null;
        $result->GeneralSettings=json_decode($result->GeneralSettings);
        return $result;
    }
}