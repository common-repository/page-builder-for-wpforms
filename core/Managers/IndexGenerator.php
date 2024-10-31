<?php

namespace rnpagebuilder\core\Managers;

use rnpagebuilder\core\db\core\DBManager;
use rnpagebuilder\core\Exception\FriendlyException;
use rnpagebuilder\core\Integration\Adapters\WPForm\Entry\WPFormEntryProcessor;
use rnpagebuilder\core\Integration\Adapters\WPForm\FormProcessor\WPFormFormProcessor;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\FormSettings;
use rnpagebuilder\core\Loader;
use rnpagebuilder\core\Managers\LogManager\LogManager;
use rnpagebuilder\core\Utils\ArrayUtils;

class IndexGenerator
{
    /**
     * @var Loader
     */
    public $Loader;
    /** @var FormSettings[] */
    public $Forms;
    public $OriginalForm;
    public $PageSize=2;
    public function __construct($loader)
    {
        $this->Loader=$loader;

    }

    public function Generate($startIndex,$endIndex){
        $this->GenerateForms($startIndex,$endIndex);
        $this->GenerateIndexes($startIndex,$endIndex);
    }

    private function GenerateForms()
    {


        $formProcessor=new WPFormFormProcessor($this->Loader);
        global $wpdb;
        $results=$wpdb->get_results("select id ID, post_title,post_content from ".$wpdb->posts." where post_type='wpforms'",'ARRAY_A');
        foreach($results as $form)
        {
            $this->Forms[]=$formProcessor->SerializeForm($form);

        }

        $this->OriginalForm=[];
        $results=$wpdb->get_results('select post_content from '.$wpdb->posts.' where post_type="wpforms"');
        foreach($results as $currentResult)
        {
            $form=json_decode($currentResult->post_content);
            if($form!=null)
            {
                $this->OriginalForm[$form->id]=$form;
            }
        }
    }

    function endsWith( $haystack, $needle ) {
        $length = strlen( $needle );
        if( !$length ) {
            return true;
        }
        return substr( $haystack, -$length ) === $needle;
    }

    private function GenerateIndexes($startIndex,$endIndex)
    {
        LogManager::LogDebug('Indexing records from '.($startIndex+1).' - '.($endIndex==0?'remaining records': ($endIndex+1)));

        if($endIndex==0)
        {
            $endIndex=$startIndex+10000;//using a big end index in case new records were added while the indexing was running
        }

       global $wpdb;
        if(!$this->endsWith($this->Loader->RECORDS_DETAIL_TABLE,'records_detail'))
            throw new FriendlyException('Sorry the index table is not right');


        $dbManager=new DBManager();
        $entries=$dbManager->GetResults("select entry_id,form_id,fields from ".$wpdb->prefix."wpforms_entries order by entry_id asc limit %d offset %d",$endIndex-$startIndex,$startIndex);

        $entryProcess=new WPFormEntryProcessor($this->Loader);
        foreach($entries as $currentEntry)
        {
            $form=ArrayUtils::Find($this->Forms,function  ($item)use($currentEntry){
                if($currentEntry->form_id==$item->OriginalId)
                    return $currentEntry;
            });

            if($form==null||!isset($this->OriginalForm[$currentEntry->form_id]))
                continue;

            $fields=json_decode($currentEntry->fields,true);
            if($fields==null)
                continue;
            try
            {
                $entry = $entryProcess->SerializeEntry($fields, $form, $this->OriginalForm[$currentEntry->form_id]);
            }catch (\Exception $ex)
            {
                LogManager::LogError('Error indexing entry '.$currentEntry->entry_id.' '.$ex->getMessage());
                throw $ex;
            }
            if($entry==null)
                continue;
            $entryProcess->SaveDetails($currentEntry->entry_id,$currentEntry->form_id,$entry);

        }

    }

    public function PrepareForIndexGeneration()
    {
        global $wpdb;
        update_option('pb_index_generated',false);
        if(!$this->endsWith($this->Loader->RECORDS_DETAIL_TABLE,'records_detail'))
            throw new FriendlyException('Sorry the index table is not right');
        $wpdb->query('truncate table '.$this->Loader->RECORDS_DETAIL_TABLE);
    }

}