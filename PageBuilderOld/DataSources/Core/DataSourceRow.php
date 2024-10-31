<?php


namespace rnpagebuilder\PageBuilderOld\DataSources\Core;


abstract class DataSourceRow
{
    public abstract function GetHTMLValue($columnId,$path=null);
    public abstract function GetStringValue($columnId,$path=null);
    public abstract function GetValue($columnId);

}