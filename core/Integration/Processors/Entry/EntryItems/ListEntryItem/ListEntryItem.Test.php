<?php /** @noinspection PhpIllegalPsrClassPathInspection */

/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/22/2019
 * Time: 5:50 AM
 */



use PHPUnit\Framework\TestCase;
use rnpagebuilder\core\Integration\Processors\Entry\EntryItems\MultipleSelectionEntryItem\ListEntryItem\ListEntryItem;
use rnpagebuilder\core\Integration\Processors\Settings\Forms\Fields\TextFieldSettings;

class ListEntryItemTest extends TestCase
{
    public function testCanAddRows(){
        $listEntryItem=new ListEntryItem();
        $listEntryItem->AddRowWithValue('','test','test');
        $listEntryItem->AddRowWithValue('','test 2','test 2');


        $this->assertEquals('test, test 2',$listEntryItem->GetText());


        $listEntryItem=new ListEntryItem();
        $row=$listEntryItem->CreateRow();
        $row->AddColumn('','test_col1','test_col1');
        $row->AddColumn('','test_col2','test_col2');

        $row=$listEntryItem->CreateRow();
        $row->AddColumn('','test2_col1','test2_col1');
        $row->AddColumn('','test2_col2','test2_col2');


        $this->assertEquals('test_col1,test_col2|test2_col1,test2_col2',$listEntryItem->GetText());




    }

    public function testCanInitializeWithOptions(){
        $listEntryItem=new ListEntryItem();
        $listEntryItem->Initialize((new TextFieldSettings())->Initialize('1','',''));
        $listEntryItem->AddRowWithValue('','test','test');
        $listEntryItem->AddRowWithValue('','test 2','test 2');
        $object=$listEntryItem->GetObjectToSave();

        $listEntryItem2=new ListEntryItem();
        $listEntryItem2->InitializeWithOptions((new TextFieldSettings())->Initialize('1','',''),$object);

        $this->assertEquals('test, test 2',$listEntryItem2->GetText());

    }

    public function testCanInitializeMultipleOptions(){
        $listEntryItem=new ListEntryItem();
        $listEntryItem->Initialize((new TextFieldSettings())->Initialize('1','',''));
        $row=$listEntryItem->CreateRow();
        $row->AddColumn('','test_col1','test_col1');
        $row->AddColumn('','test_col2','test_col2');

        $row=$listEntryItem->CreateRow();
        $row->AddColumn('','test2_col1','test2_col1');
        $row->AddColumn('','test2_col2','test2_col2');
        $object=$listEntryItem->GetObjectToSave();

        $listEntryItem2=new ListEntryItem();
        $listEntryItem2->InitializeWithOptions((new TextFieldSettings())->Initialize('1','',''),$object);

        $this->assertEquals('test_col1,test_col2|test2_col1,test2_col2',$listEntryItem->GetText());
    }
}