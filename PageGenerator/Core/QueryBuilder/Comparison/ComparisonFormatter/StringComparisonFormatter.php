<?php


namespace rnpagebuilder\PageGenerator\Core\QueryBuilder\Comparison\ComparisonFormatter;


use rnpagebuilder\Utilities\Sanitizer;

class StringComparisonFormatter extends ComparisonFormatterBase
{

    public function Format($value)
    {
        global $wpdb;
        $value=Sanitizer::SanitizeString($value);
        return $wpdb->prepare('%s',$value);
    }
}