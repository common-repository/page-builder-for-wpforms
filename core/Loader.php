<?php

namespace rnpagebuilder\core;
use rnpagebuilder\ajax\PageBuilderAjax;
use rnpagebuilder\ajax\PageBuilderRunnableAjax;
use rnpagebuilder\ajax\PageListAjax;
use rnpagebuilder\ajax\Settings;
use rnpagebuilder\core\db\core\DBManager;
use rnpagebuilder\core\Integration\Processors\Entry\Retriever\EntryRetrieverBase;
use rnpagebuilder\core\Integration\Processors\Loader\ProcessorLoaderBase;
use rnpagebuilder\core\Integration\UserIntegration;
use rnpagebuilder\core\Managers\LogManager\LogManager;
use rnpagebuilder\PageGenerator\Core\TwigManager\TwigManager;
use rnpagebuilder\pr\core\PRLoader;

abstract class Loader extends PluginBase
{
    public $PageTable;
    public $EntryMetaTable;
    /** @var ProcessorLoaderBase */
    public $ProcessorLoader;
    public $FormConfigTable;
    public $WPFormRecordTable;
    public $RECORDS_DETAIL_TABLE;
    /** @var PRLoader */
    public $PRLoader;
    public $PageBuilderAjax;
    public $EntryFreeTable;
    public $Settings;
    public $Twig;
    public $TEMPLATE_TABLE;

    public static $Instance;
    public function __construct($prefix,$basePrefix, $dbVersion, $fileVersion,$rootFilePath,$config=null)
    {
        global $wpdb;
        self::$Instance=$this;

        $this->EntryMetaTable=$wpdb->prefix.$prefix.'_entry_meta';
        $this->PageTable= $wpdb->prefix.$prefix.'_page';
        $this->FormConfigTable= $wpdb->prefix.$prefix.'_form_config';
        $this->EntryFreeTable=$wpdb->prefix.$prefix.'_records';
        $this->RECORDS_DETAIL_TABLE=$wpdb->prefix.$prefix.'_'.'records_detail';
        $this->TEMPLATE_TABLE=$wpdb->prefix.$prefix.'_'.'template';
        parent::__construct($prefix, $dbVersion, $fileVersion,$rootFilePath,$config);
        if($prefix==null)
            return;
        $me=$this;
        add_filter('rn_get_page_builder_loader',function ()use($me){
            return $me;
        });
        $this->PageBuilderAjax=new PageBuilderAjax($this);
        new PageBuilderRunnableAjax($this);
        new PageListAjax($this);
        $this->Settings=new Settings($this);
        $this->WPFormRecordTable=$wpdb->prefix.'wpforms_entries';
        LogManager::Initialize($this);
        add_action('admin_notices', array($this,'AddNotice'));

    }

    public abstract function GetRecordsTableName();
    public function AddNotice(){
        if(get_option('pb_index_generated',false)===false)
            echo '<div class="notice notice-success is-dismissible wpf_views-notice ">
                 <div style="display: flex;align-items: center">
                      <img style="width: 150px" src="'.$this->URL.'images/icon.png"/>
                      <div style="margin-left: 3px">
                       <p style="margin: 0">
                       Thanks for using Page Builder for WPForms, in order to be able to filter the records in the pages we need to index your entries first, this process just need to be executed once.
                       </p>
                       <p>
                        <a href="'.esc_Attr(admin_url("admin.php?page=rnpagebuilder_settings")).'">You can start the indexing process here</a>
                        </p>
                        </div>
                </div>
             </div>';
    }

    public function GetTwigManager($paths=[]){

        if($this->Twig==null)
        {
            $this->Twig=new TwigManager($this,$paths);
        }
        return $this->Twig;
    }



    public abstract function GetSubPrefix();
    public function GetAllRenderersNames(){
        return array(
            'TextRenderer',
            'TextAreaRenderer',
            'SelectRenderer',
            'RadioRenderer',
            'NumberRenderer',
            'CheckboxRenderer',
            'ComposedRenderer',
            'FileRenderer',
            'SignatureRenderer'
        );
    }

    public function GetUserIntegration(){
        return new UserIntegration($this);
    }

    public function GetDBManager(){
        return new DBManager();
    }
    public function  IsPR(){
        return file_exists($this->DIR.'pr');
    }

    /**
     * @return EntryRetrieverBase
     */
    public abstract function CreateEntryRetriever();
    public abstract function GetPurchaseURL();
    public function CreateHooks()
    {
        add_filter('rnpagebuilder_get_loader',function(){return $this;});
    }

    public abstract function AddAdvertisementParams($params);

    public function OnCreateTable()
    {

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        global $wpdb;
        $charset_collate = '';

        if ( ! empty ( $wpdb->charset ) )
            $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";

        if ( ! empty ( $wpdb->collate ) )
            $charset_collate .= " COLLATE $wpdb->collate";


        $sql = "CREATE TABLE " . $this->EntryFreeTable . " (
                entry_id bigint AUTO_INCREMENT,
                form_id bigint,
                starred tinyint,
                fields MEDIUMTEXT,
                date datetime,
                user_id bigint,
                PRIMARY KEY  (entry_id)
                ) $charset_collate;";
        \dbDelta($sql);

        $sql = "CREATE TABLE " . $this->PageTable . " (
                id INT AUTO_INCREMENT,
                areas MEDIUMTEXT,
                filter MEDIUMTEXT,
                general_settings MEDIUMTEXT,
                sort MEDIUMTEXT,
                formid varchar(10),
                formulas MEDIUMTEXT,
                type varchar(50),
                name VARCHAR(200) NOT NULL,
                PRIMARY KEY  (id)
                ) $charset_collate;";
        \dbDelta($sql);

        $sql = "CREATE TABLE " . $this->EntryMetaTable . " (
                id INT AUTO_INCREMENT,
                entry_id BIGINT,
                name VARCHAR(200),
                value MEDIUMTEXT,
                KEY entry_id (entry_id),
                KEY name (name),
                PRIMARY KEY  (id)
                ) $charset_collate;";
        \dbDelta($sql);

        $sql = "CREATE TABLE " . $this->TEMPLATE_TABLE . " (
                id INT AUTO_INCREMENT,
                name VARCHAR(1000),
                kind VARCHAR(20),
                type VARCHAR(20),
                options MEDIUMTEXT,
                
                PRIMARY KEY  (id)
                ) $charset_collate;";
        \dbDelta($sql);


        $sql="CREATE TABLE ".$this->RECORDS_DETAIL_TABLE." (
                entry_detail_id int AUTO_INCREMENT,
                entry_id int,
                form_id bigint,
                uniq_id int,
                type VARCHAR(30),
                sub_type VARCHAR(300),
                original_type VARCHAR(300),
                field_id varchar(50),
                value MEDIUMTEXT,
                exvalue1 MEDIUMTEXT,
                exvalue2 MEDIUMTEXT,
                exvalue3 MEDIUMTEXT,
                exvalue4 MEDIUMTEXT,
                exvalue5 MEDIUMTEXT,
                exvalue6 MEDIUMTEXT,
                path_id VARCHAR(100),
                numericvalue DOUBLE,        
                numericvalue2 DOUBLE,
                datevalue DATETIME,
                datevalue2 DATETIME,
                PRIMARY KEY  (entry_detail_id),
                KEY path_id (path_id),
                KEY entry_id (entry_id),
                KEY field_id (field_id),
                KEY numericvalue (numericvalue),   
                KEY numericvalue2 (numericvalue2),   
                KEY datevalue (datevalue),
                KEY datevalue2 (datevalue2),
                FULLTEXT KEY value (value)
        ) $charset_collate;";
        dbDelta($sql);


        $sql = "CREATE TABLE " . $this->FormConfigTable . " (
                id INT AUTO_INCREMENT,
                original_id BIGINT,
                name VARCHAR(200) NOT NULL,
                fields MEDIUMTEXT,
                notifications MEDIUMTEXT,
                PRIMARY KEY  (id)
                ) $charset_collate;";
        \dbDelta($sql);


    }

    public abstract function GetProductItemId();



}