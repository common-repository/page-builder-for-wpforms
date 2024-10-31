<?php


namespace rnpagebuilder\core\db\core;


use Exception;
use rnpagebuilder\core\Exception\ExceptionSeverity;
use rnpagebuilder\core\Exception\FriendlyException;

class DBManager
{
    public $lastQuery;
    public $ThrowError;
    /**
     * DBManager constructor.
     */
    public function __construct($throwErrorOnFailure=false)
    {
        $this->ThrowError=$throwErrorOnFailure;
    }

    public function GetPrefix(){
        global $wpdb;
        return $wpdb->prefix;
    }


    public function EscSQLName($sqlName){
        return \esc_sql($sqlName);
    }

    public function GetResults($query,...$args)
    {
        return \call_user_func_array(array($this,'GetResultsWithOffset'),\array_merge([$query,null,null],$args));
    }

    public function GetResultsWithOffset($query,$limit=-1,$offset=-1,...$args)
    {
        global $wpdb;
        $wpdb->suppress_errors(true);
        if(count($args)>0)
            $query=$wpdb->prepare($query,$args);

        $this->lastQuery=$query;
        if($limit>0)
        {
            $query.=' limit ';
            if($offset>0)
                $query.=\intval($offset). ', ';
            $query.=\floatval($limit).' ';
        }

        $result= $wpdb->get_results($query);

        if($result===false||$wpdb->last_error!='')
        {
            if($this->ThrowError)
                throw new FriendlyException('An error occurred while querying the database, please check the log for more details ' ,"Query Executed:".$wpdb->last_query.' Error:'.$wpdb->last_error,ExceptionSeverity::$FATAL);

            return array();
        }

        return $result;
    }

    public function Insert($tableName,$data)
    {
        global $wpdb;
        $wpdb->suppress_errors(true);

        $result=$wpdb->insert($tableName,$data);

        if($result===false)
            if($this->ThrowError)
                throw new FriendlyException('An error occurred while inserting the record, please check the log for more details ' ,"Query Executed:".$wpdb->last_query.' Error:'.$wpdb->last_error,ExceptionSeverity::$FATAL);
            else
                return false;

        return $wpdb->insert_id;
    }

    public function Update($table, $data,$where)
    {
        global $wpdb;
        $wpdb->suppress_errors(true);

        $result=$wpdb->update($table,$data,$where);

        if($result===false)
            if($this->ThrowError)
                throw new FriendlyException('An error occurred while updating the record, please check the log for more details ' ,"Query Executed:".$wpdb->last_query.' Error:'.$wpdb->last_error,ExceptionSeverity::$FATAL);
            else
                return false;
        return true;

    }

    public function GetVar($query,...$args)
    {
        $result=\call_user_func_array(array($this,'GetResults'),\array_merge([$query],$args));

        if(count($result)==0)
            return null;

        return current((array)$result[0]);


    }

    public function GetResult($query,...$args)
    {
        $result=\call_user_func_array(array($this,'GetResults'),\array_merge([$query],$args));

        if(count($result)==0)
            return null;

        return $result[0];

    }

    public function Delete($table, $where)
    {
        global $wpdb;
        $wpdb->suppress_errors(true);
        $result=$wpdb->delete($table,$where);

        if($result===false)
            if($this->ThrowError)
                throw new FriendlyException('An error occurred while deleting the record, please check the log for more details ',"Query Executed:".$wpdb->last_query.' Error:'.$wpdb->last_error ,ExceptionSeverity::$FATAL);
            else
                return false;
        return true;
    }

    public function EscapeLike($value)
    {
        global $wpdb;
        return $wpdb->esc_like($value);
    }

    public function EscapeString($valueToEscape)
    {
        global $wpdb;
        return $wpdb->prepare('%s',$valueToEscape);
    }

    public function EscapeNumber($valueToEscape)
    {
        global $wpdb;
        return $wpdb->prepare('%d',$valueToEscape);
    }

    public function Execute($query)
    {
        global $wpdb;
        return $wpdb->query($query);
    }


}