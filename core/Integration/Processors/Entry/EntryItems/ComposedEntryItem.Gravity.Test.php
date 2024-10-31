<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/22/2019
 * Time: 5:50 AM
 */

namespace rnpagebuilder\core\Integration\Processors\Entry\EntryItems;


use PHPUnit\Framework\TestCase;

class GravityComposedEntryItem extends TestCase
{


    public function testNameWorks(){
        $nameSettings=\json_decode('{"Items":[{"Id":"1.2","Path":["1.2"],"Label":"Prefix","AddCommaBefore":false},{"Id":"1.3","Path":["1.3"],"Label":"First","AddCommaBefore":false},{"Id":"1.4","Path":["1.4"],"Label":"Middle","AddCommaBefore":false},{"Id":"1.6","Path":["1.6"],"Label":"Last","AddCommaBefore":false},{"Id":"1.8","Path":["1.8"],"Label":"Suffix","AddCommaBefore":false}],"Id":1,"Label":"Name","Type":"Composed","SubType":"name"}');
        $values=\json_decode('{"id":"177","status":"active","form_id":"12","ip":"::1","source_url":"http:\/\/localhost:9090\/smartforms\/?gf_page=preview&id=12","currency":"USD","post_id":null,"date_created":"2020-11-12 14:27:46","date_updated":"2020-11-12 14:27:46","is_starred":0,"is_read":0,"user_agent":"Mozilla\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/86.0.4240.183 Safari\/537.36","payment_status":null,"payment_date":null,"payment_amount":null,"payment_method":"","transaction_id":null,"is_fulfilled":null,"created_by":"1","transaction_type":null,"1.2":"","1.3":"456","1.4":"","1.6":"465","1.8":"","4":"a:2:{i:0;s:3:\"456\";i:1;s:3:\"465\";}","6":"34456","7":"cat2:76","3":"2020-11-19","5":"567567"}');
        $composedEntryItem=(new ComposedEntryItem())->Initialize($nameSettings)->SetValue($values);

        $this->assertEquals('456 465',$composedEntryItem->GetText());
    }

}