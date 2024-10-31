<?php


namespace rnpagebuilder\Utilities;


class Sanitizer
{
    public static function SanitizeString($value)
    {
        if($value==null)
            return '';

        if(is_array($value))
            return '';
        if(is_object($value))
            return '';

        return strval($value);
    }

    public static function SanitizeSTDClass($value)
    {
        if($value==null)
            return null;

        if(is_array($value))
            return (object)$value;

        if(is_object($value))
            return $value;

        return null;


    }

    public static function SanitizeNumber($value,$defaultValue=0)
    {
        if($value==null||!is_numeric($value))
            return $defaultValue;

        return floatval($value);

    }

    public static function SanitizeArray($value,$convertToArrayIfPossible=false)
    {
        if($value==null)
            return [];

        if(is_array($value))
            return $value;

        if(is_scalar($value))
        {
            if ($convertToArrayIfPossible)
                return [$value];
            else
                return [];
        }

        return [];






    }

    public static function SanitizeBoolean($value,$defaultValue=false)
    {
        if($value===null)
            return $defaultValue;

        if(is_bool($value))
            return $value;

        return $defaultValue;

    }

    public static function GetNumericValueFromPath($value, $path,$defaultValue=0)
    {
        return Sanitizer::SanitizeNumber(Sanitizer::SanitizeString(Sanitizer::GetValueFromPath($value,$path,$defaultValue)),$defaultValue);
    }

    public static function GetStringValueFromPath($value, $path,$defaultValue=null)
    {
        return Sanitizer::SanitizeString(Sanitizer::GetValueFromPath($value,$path,$defaultValue));
    }

    public static function GetNumberValueFromPath($value, $path,$defaultValue=null)
    {
        return Sanitizer::SanitizeNumber(Sanitizer::GetValueFromPath($value,$path,$defaultValue));
    }

    public static function GetValueFromPath($obj, $path, $defaultValue=null)
    {
        if(!is_array($path))
            $path=[$path];
        if($obj==null)
            return null;

        while(($currentPath=array_shift($path))!==null)
        {
            if(is_array($obj))
            {
                if(isset($obj[$currentPath]))
                    $obj=$obj[$currentPath];
                else
                    return $defaultValue;
            }else{
                if(isset($obj->{$currentPath}))
                    $obj=$obj->{$currentPath};
                else
                    return $defaultValue;
            }
        }

        return $obj;
    }

}