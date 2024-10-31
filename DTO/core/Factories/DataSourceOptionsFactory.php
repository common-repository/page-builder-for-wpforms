<?php


namespace rnpagebuilder\DTO\core\Factories;


use rnpagebuilder\DTO\DataSourceBaseOptionsDTO;
use rnpagebuilder\DTO\DataSourceTypeEnumDTO;
use rnpagebuilder\DTO\FormDataSourceOptionsDTO;

class DataSourceOptionsFactory
{
    public static function GetOptions($value)
    {
        if($value==null)
            return [];
        $dataSources=[];
        foreach($value as $currentDataSource)
        {
            $options=self::GetDataSourceOptions($currentDataSource);
            if($options!=null)
                $dataSources[]=$options;
        }

        return $dataSources;
    }

    /**
     * @param $options DataSourceBaseOptionsDTO
     */
    public static function GetDataSourceOptions($options)
    {
        switch ($options->Type)
        {
            case DataSourceTypeEnumDTO::$Form:
                return (new FormDataSourceOptionsDTO())->Merge($options);
        }
    }

}