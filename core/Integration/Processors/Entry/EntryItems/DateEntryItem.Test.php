<?php


namespace rnpagebuilder\core\Integration\Processors\Entry\EntryItems;


use PHPUnit\Framework\TestCase;
use rnpagebuilder\core\Integration\Processors\Entry\HTMLFormatters\PHPFormatterBase;

class DateEntryItemTest extends TestCase
{
   public function testValidValueWorks(){
       $datEntry=(new DateEntryItem())->Initialize(\json_decode('{"DateFormat":null,"Id":3,"Label":"Date","Type":"Date","SubType":"date"}'))
           ->SetUnix(\strtotime('2020-11-19'))->SetValue('2020-11-19');

       $this->assertEquals('2020-11-19',$datEntry->GetText());;
   }

    public function invalidValidValueWorks(){
        $datEntry=(new DateEntryItem())->Initialize(\json_decode('{"DateFormat":null,"Id":3,"Label":"Date","Type":"Date","SubType":"date"}'))
            ->SetUnix(\strtotime(''))->SetValue('');

        $this->assertEquals('',$datEntry->GetText());
        $this->assertEquals($datEntry->Unix,0);
    }
}