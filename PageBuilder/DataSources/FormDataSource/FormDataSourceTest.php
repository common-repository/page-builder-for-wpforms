<?php


namespace rnpagebuilder\PageBuilder\DataSources\FormDataSource;


use PHPUnit\Framework\TestCase;
use rnpagebuilder\DTO\ConditionGroupOptionsDTO;
use rnpagebuilder\DTO\ConditionLineOptionsDTO;
use rnpagebuilder\DTO\DataSourceOptionsDTO;
use rnpagebuilder\DTO\FilterConditionOptionsDTO;
use rnpagebuilder\DTO\FormDataSourceOptionsDTO;
use rnpagebuilder\DTO\PageBuilderOptionsDTO;
use rnpagebuilder\DTO\RNPageOptionsDTO;
use rnpagebuilder\PageBuilder\DataSources\Core\DataSourceBase;
use rnpagebuilder\PageBuilder\PageBuilderGenerator;
use rnpagebuilder\test\phpunit\MockLoader;


class FormDataSourceTest extends TestCase
{

    public function GenerateCondition($formId){
        $options=new FormDataSourceOptionsDTO();
        $options->FormId=$formId;
        $options->Columns=[];
        $options->FieldsUsed=[];
        //StringFilter
        $options->Condition=new FilterConditionOptionsDTO();
        $options->Condition->ConditionGroups=[];
        $options->Condition->ConditionGroups[]=new ConditionGroupOptionsDTO();
        return $options;
    }

    public function GenerateConditionLine($fieldId,$value,$comparison,$subType='Text',$pathId='Value',$type='Standard')
    {
        $conditionLine=new ConditionLineOptionsDTO();
        $conditionLine->Id=1;
        $conditionLine->Value=$value;
        $conditionLine->Comparison=$comparison;
        $conditionLine->FieldId=$fieldId;
        $conditionLine->PathId=$pathId;
        $conditionLine->Type=$type;
        $conditionLine->SubType=$subType;
        return $conditionLine;
    }


    public function testQueryWithoutFiltersWork(){
        $options=new FormDataSourceOptionsDTO();
        $options->Id="1";
        $options->FormId='100';
        $options->Columns=[];
        $options->FieldsUsed=[];
        $options->Condition=new FilterConditionOptionsDTO();
        $options->Condition->ConditionGroups=[];
        $pageOptions=new RNPageOptionsDTO();
        $pageOptions->DataSources=[];
        $pageBuilderOptions=new PageBuilderOptionsDTO();
        $pageBuilderOptions->Page=$pageOptions;

        $pageBuilder=new PageBuilderGenerator(new MockLoader(),$pageBuilderOptions);
        $formDataSource=new FormDataSource($pageBuilder,$options);
        $pageOptions->DataSources[]=$formDataSource;
        $rows=$formDataSource->GetRows(2,0);
        $this->assertEquals(2,count($rows->Rows));
    }

//#region "String Comparisons"
    public function testStringEqualsWork(){
        $pageOptions=new RNPageOptionsDTO();
        $pageOptions->DataSources=[];

        $condition=$this->GenerateCondition(100);
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__entryid','201','Equal');
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine(1,'test 2','Equal');


        $pageBuilderOptions=new PageBuilderOptionsDTO();
        $pageBuilderOptions->Page=$pageOptions;
        $pageBuilder=new PageBuilderGenerator(new MockLoader(),$pageBuilderOptions);
        $formDataSource=new FormDataSource($pageBuilder,$condition);
        $pageOptions->DataSources[]=$formDataSource;

        $rows=$formDataSource->GetRows();
        $this->assertEquals(1,count($rows->Rows));
        $this->assertStringContainsString("value = 'test 2'",$formDataSource->LastQuery);
        $this->assertEquals($rows->Rows[0]->Reference,'60350df06d810ed76683594a18ffce545f025d874');
    }

    public function testStringNotEqualsWork(){
        $pageOptions=new RNPageOptionsDTO();
        $pageOptions->DataSources=[];

        $condition=$this->GenerateCondition(100);
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__entryid','201','Equal');
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine(1,'test 2','NotEqual');

        $pageBuilderOptions=new PageBuilderOptionsDTO();
        $pageBuilderOptions->Page=$pageOptions;
        $pageBuilder=new PageBuilderGenerator(new MockLoader(),$pageBuilderOptions);
        $formDataSource=new FormDataSource($pageBuilder,$condition);
        $pageOptions->DataSources[]=$formDataSource;

        $rows=$formDataSource->GetRows();
        $this->assertEquals(0,count($rows->Rows));
        $this->assertStringContainsString("value <> 'test 2'",$formDataSource->LastQuery);

    }

    public function testStringNotEqualsWithNotInsertedRecordWork(){
        $pageOptions=new RNPageOptionsDTO();
        $pageOptions->DataSources=[];

        $condition=$this->GenerateCondition(100);
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__entryid','202','Equal');
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine(1,'test 2','NotEqual');

        $pageBuilderOptions=new PageBuilderOptionsDTO();
        $pageBuilderOptions->Page=$pageOptions;
        $pageBuilder=new PageBuilderGenerator(new MockLoader(),$pageBuilderOptions);
        $formDataSource=new FormDataSource($pageBuilder,$condition);
        $pageOptions->DataSources[]=$formDataSource;

        $rows=$formDataSource->GetRows();
        $this->assertEquals(1,count($rows->Rows));
        $this->assertStringContainsString("value <> 'test 2'",$formDataSource->LastQuery);

    }
//#endregion


//#region "Number Comparisons"
    public function testNumberEqualsWork(){
        $pageOptions=new RNPageOptionsDTO();
        $pageOptions->DataSources=[];

        $condition=$this->GenerateCondition(100);
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__entryid','201','Equal');
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine(5,32,'Equal','Number');

        $pageBuilderOptions=new PageBuilderOptionsDTO();
        $pageBuilderOptions->Page=$pageOptions;
        $pageBuilder=new PageBuilderGenerator(new MockLoader(),$pageBuilderOptions);
        $formDataSource=new FormDataSource($pageBuilder,$condition);
        $pageOptions->DataSources[]=$formDataSource;

        $rows=$formDataSource->GetRows();
        $this->assertEquals(1,count($rows->Rows));
        $this->assertStringContainsString("value = 32",$formDataSource->LastQuery);
        $this->assertEquals($rows->Rows[0]->Reference,'60350df06d810ed76683594a18ffce545f025d874');
    }


    public function testNumberNotEqualsWork(){
        $pageOptions=new RNPageOptionsDTO();
        $pageOptions->DataSources=[];

        $condition=$this->GenerateCondition(100);
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__entryid','201','Equal');
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine(5,32,'NotEqual','Number');

        $pageBuilderOptions=new PageBuilderOptionsDTO();
        $pageBuilderOptions->Page=$pageOptions;
        $pageBuilder=new PageBuilderGenerator(new MockLoader(),$pageBuilderOptions);
        $formDataSource=new FormDataSource($pageBuilder,$condition);
        $pageOptions->DataSources[]=$formDataSource;

        $rows=$formDataSource->GetRows();
        $this->assertEquals(0,count($rows->Rows));
        $this->assertStringContainsString("value <> 32",$formDataSource->LastQuery);

    }

    public function testNumberNotEqualsWithNotInsertedRecordWork(){
        $pageOptions=new RNPageOptionsDTO();
        $pageOptions->DataSources=[];

        $condition=$this->GenerateCondition(100);
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__entryid','202','Equal');
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine(5,32,'NotEqual','Number');

        $pageBuilderOptions=new PageBuilderOptionsDTO();
        $pageBuilderOptions->Page=$pageOptions;
        $pageBuilder=new PageBuilderGenerator(new MockLoader(),$pageBuilderOptions);
        $formDataSource=new FormDataSource($pageBuilder,$condition);
        $pageOptions->DataSources[]=$formDataSource;

        $rows=$formDataSource->GetRows();
        $this->assertEquals(1,count($rows->Rows));
        $this->assertStringContainsString("value <> 32",$formDataSource->LastQuery);

    }


    public function testNumberGreatOrEqualAndLessOrEqualRecordWork(){
        $pageOptions=new RNPageOptionsDTO();
        $pageOptions->DataSources=[];

        $condition=$this->GenerateCondition(100);
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__entryid','200','GreaterOrEqualThan','Number');
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__entryid','201','LessOrEqualThan','Number');


        $pageBuilderOptions=new PageBuilderOptionsDTO();
        $pageBuilderOptions->Page=$pageOptions;
        $pageBuilder=new PageBuilderGenerator(new MockLoader(),$pageBuilderOptions);
        $formDataSource=new FormDataSource($pageBuilder,$condition);
        $pageOptions->DataSources[]=$formDataSource;

        $rows=$formDataSource->GetRows();
        $this->assertEquals(2,count($rows->Rows));
        $this->assertStringContainsString("id >= 200",$formDataSource->LastQuery);
        $this->assertStringContainsString("id <= 201",$formDataSource->LastQuery);

    }

    public function testNumberGreatAndLessRecordWork(){
        $pageOptions=new RNPageOptionsDTO();
        $pageOptions->DataSources=[];

        $condition=$this->GenerateCondition(100);
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__entryid','199','GreaterThan','Number');
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__entryid','202','LessThan','Number');


        $pageBuilderOptions=new PageBuilderOptionsDTO();
        $pageBuilderOptions->Page=$pageOptions;
        $pageBuilder=new PageBuilderGenerator(new MockLoader(),$pageBuilderOptions);
        $formDataSource=new FormDataSource($pageBuilder,$condition);
        $pageOptions->DataSources[]=$formDataSource;

        $rows=$formDataSource->GetRows();
        $this->assertEquals(2,count($rows->Rows));
        $this->assertStringContainsString("id > 199",$formDataSource->LastQuery);
        $this->assertStringContainsString("id < 202",$formDataSource->LastQuery);

    }
//#endregion



//#region "Multiple Comparisons"
    public function testMultipleContainsWork(){
        $pageOptions=new RNPageOptionsDTO();
        $pageOptions->DataSources=[];

        $condition=$this->GenerateCondition(100);
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__entryid','201','Equal');
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine(6,['First Choice'],'Contains','Multiple');

        $pageBuilderOptions=new PageBuilderOptionsDTO();
        $pageBuilderOptions->Page=$pageOptions;
        $pageBuilder=new PageBuilderGenerator(new MockLoader(),$pageBuilderOptions);
        $formDataSource=new FormDataSource($pageBuilder,$condition);
        $pageOptions->DataSources[]=$formDataSource;

        $rows=$formDataSource->GetRows();
        $this->assertEquals(1,count($rows->Rows));
        $this->assertStringContainsString("value in ('First Choice')",$formDataSource->LastQuery);
        $this->assertEquals($rows->Rows[0]->Reference,'60350df06d810ed76683594a18ffce545f025d874');
    }


    public function testMultipleContainsReturnsOnlyOneRecordWork(){
        $pageOptions=new RNPageOptionsDTO();
        $pageOptions->DataSources=[];

        $condition=$this->GenerateCondition(100);
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__entryid','201','Equal');
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine(6,['First Choice','Second Choice'],'Contains','Multiple');

        $pageBuilderOptions=new PageBuilderOptionsDTO();
        $pageBuilderOptions->Page=$pageOptions;
        $pageBuilder=new PageBuilderGenerator(new MockLoader(),$pageBuilderOptions);
        $formDataSource=new FormDataSource($pageBuilder,$condition);
        $pageOptions->DataSources[]=$formDataSource;

        $rows=$formDataSource->GetRows();
        $this->assertEquals(1,count($rows->Rows));
        $this->assertStringContainsString("value in ('First Choice','Second Choice')",$formDataSource->LastQuery);
        $this->assertEquals($rows->Rows[0]->Reference,'60350df06d810ed76683594a18ffce545f025d874');
    }

    public function testNotContainsWork(){
        $pageOptions=new RNPageOptionsDTO();
        $pageOptions->DataSources=[];

        $condition=$this->GenerateCondition(100);
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__entryid','200','GreaterOrEqualThan');
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__entryid','202','LessOrEqualThan');
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine(6,['Second Choice'],'NotContains','Multiple');

        $pageBuilderOptions=new PageBuilderOptionsDTO();
        $pageBuilderOptions->Page=$pageOptions;
        $pageBuilder=new PageBuilderGenerator(new MockLoader(),$pageBuilderOptions);
        $formDataSource=new FormDataSource($pageBuilder,$condition);
        $pageOptions->DataSources[]=$formDataSource;

        $rows=$formDataSource->GetRows();
        $this->assertEquals(2,count($rows->Rows));
        $this->assertStringContainsString("value in ('Second Choice')",$formDataSource->LastQuery);
        $this->assertContains($rows->Rows[0]->Reference,['60350d4933ef385b70a1a0037545afb51237e11f9','60350df06d810ed76683594a18ffce545f025d874']);
        $this->assertContains($rows->Rows[1]->Reference,['60350d4933ef385b70a1a0037545afb51237e11f9','60350df06d810ed76683594a18ffce545f025d874']);
    }

    public function testIsEmptyWork(){
        $pageOptions=new RNPageOptionsDTO();
        $pageOptions->DataSources=[];

        $condition=$this->GenerateCondition(100);
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__entryid','200','GreaterOrEqualThan');
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__entryid','202','LessOrEqualThan');
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine(6,null,'IsEmpty','Multiple');

        $pageBuilderOptions=new PageBuilderOptionsDTO();
        $pageBuilderOptions->Page=$pageOptions;
        $pageBuilder=new PageBuilderGenerator(new MockLoader(),$pageBuilderOptions);
        $formDataSource=new FormDataSource($pageBuilder,$condition);
        $pageOptions->DataSources[]=$formDataSource;

        $rows=$formDataSource->GetRows();
        $this->assertEquals(1,count($rows->Rows));
        $this->assertStringContainsString("is null",$formDataSource->LastQuery);
        $this->assertContains($rows->Rows[0]->Reference,['60350df06d810ed76683594a18ffce545f025d874']);
    }

    public function testIsNotEmptyWork(){
        $pageOptions=new RNPageOptionsDTO();
        $pageOptions->DataSources=[];

        $condition=$this->GenerateCondition(100);
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__entryid','200','GreaterOrEqualThan');
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__entryid','202','LessOrEqualThan');
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine(6,null,'IsNotEmpty','Multiple');


        $pageBuilderOptions=new PageBuilderOptionsDTO();
        $pageBuilderOptions->Page=$pageOptions;
        $pageBuilder=new PageBuilderGenerator(new MockLoader(),$pageBuilderOptions);
        $formDataSource=new FormDataSource($pageBuilder,$condition);
        $pageOptions->DataSources[]=$formDataSource;

        $rows=$formDataSource->GetRows();
        $this->assertEquals(2,count($rows->Rows));
        $this->assertStringContainsString("is null",$formDataSource->LastQuery);
        $this->assertContains($rows->Rows[0]->Reference,['60350d4933ef385b70a1a0037545afb51237e11f9','60350df06d810ed76683594a18ffce545f025d874']);
        $this->assertContains($rows->Rows[1]->Reference,['60350d4933ef385b70a1a0037545afb51237e11f9','60350df06d810ed76683594a18ffce545f025d874']);
    }

//#endregion


//#region "Composed Comparisons"
    public function testComposedMergedSearchWork(){
        $pageOptions=new RNPageOptionsDTO();
        $pageOptions->DataSources=[];

        $condition=$this->GenerateCondition(100);
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__entryid','200','Equal');
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine(10,'asfdasdf 3232 asd AL 33333','Equal','Composed');

        $pageBuilderOptions=new PageBuilderOptionsDTO();
        $pageBuilderOptions->Page=$pageOptions;
        $pageBuilder=new PageBuilderGenerator(new MockLoader(),$pageBuilderOptions);
        $formDataSource=new FormDataSource($pageBuilder,$condition);
        $pageOptions->DataSources[]=$formDataSource;

        $rows=$formDataSource->GetRows();
        $this->assertEquals(1,count($rows->Rows));
        $this->assertStringContainsString("value = 'asfdasdf 3232 asd AL 33333'",$formDataSource->LastQuery);
        $this->assertContains($rows->Rows[0]->Reference,['60350d4933ef385b70a1a0037545afb51237e11f9']);
    }

    public function testComposedSectionSearchWork(){
        $pageOptions=new RNPageOptionsDTO();
        $pageOptions->DataSources=[];

        $condition=$this->GenerateCondition(100);
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__entryid','200','Equal');
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine(10,'asfdasdf','Equal','Composed','Address1');

        $pageBuilderOptions=new PageBuilderOptionsDTO();
        $pageBuilderOptions->Page=$pageOptions;
        $pageBuilder=new PageBuilderGenerator(new MockLoader(),$pageBuilderOptions);
        $formDataSource=new FormDataSource($pageBuilder,$condition);
        $pageOptions->DataSources[]=$formDataSource;

        $rows=$formDataSource->GetRows();
        $this->assertEquals(1,count($rows->Rows));
        $this->assertStringContainsString("value = 'asfdasdf'",$formDataSource->LastQuery);
        $this->assertContains($rows->Rows[0]->Reference,['60350d4933ef385b70a1a0037545afb51237e11f9']);
    }
//#endregion

//#region "Date Comparisons"
    public function testDateEqualWorks(){
        $pageOptions=new RNPageOptionsDTO();
        $pageOptions->DataSources=[];

        $condition=$this->GenerateCondition(100);
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__entryid','201','Equal');
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine(12,'1612137600','Equal','Date');

        $pageBuilderOptions=new PageBuilderOptionsDTO();
        $pageBuilderOptions->Page=$pageOptions;
        $pageBuilder=new PageBuilderGenerator(new MockLoader(),$pageBuilderOptions);
        $formDataSource=new FormDataSource($pageBuilder,$condition);
        $pageOptions->DataSources[]=$formDataSource;

        $rows=$formDataSource->GetRows();
        $this->assertEquals(1,count($rows->Rows));
        $this->assertStringContainsString("numericvalue = 1612137600",$formDataSource->LastQuery);
        $this->assertContains($rows->Rows[0]->Reference,['60350df06d810ed76683594a18ffce545f025d874']);
    }
//endregion

//#region "User Comparisons"
    public function testUserEqualWorks(){
        $pageOptions=new RNPageOptionsDTO();
        $pageOptions->DataSources=[];

        $condition=$this->GenerateCondition(100);
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__entryid','201','Equal');
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__userId',[1],'UserIs','User');

        $pageBuilderOptions=new PageBuilderOptionsDTO();
        $pageBuilderOptions->Page=$pageOptions;
        $pageBuilder=new PageBuilderGenerator(new MockLoader(),$pageBuilderOptions);
        $formDataSource=new FormDataSource($pageBuilder,$condition);
        $pageOptions->DataSources[]=$formDataSource;

        $rows=$formDataSource->GetRows();
        $this->assertEquals(1,count($rows->Rows));
        $this->assertStringContainsString("user_id in (1)",$formDataSource->LastQuery);
        $this->assertEquals($rows->Rows[0]->Reference,'60350df06d810ed76683594a18ffce545f025d874');
    }

    public function testUserNotEqualWorks(){
        $pageOptions=new RNPageOptionsDTO();
        $pageOptions->DataSources=[];

        $condition=$this->GenerateCondition(100);
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__entryid','201','Equal');
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__userId',[2,3],'UserIsNot','User');

        $pageBuilderOptions=new PageBuilderOptionsDTO();
        $pageBuilderOptions->Page=$pageOptions;
        $pageBuilder=new PageBuilderGenerator(new MockLoader(),$pageBuilderOptions);
        $formDataSource=new FormDataSource($pageBuilder,$condition);
        $pageOptions->DataSources[]=$formDataSource;

        $rows=$formDataSource->GetRows();
        $this->assertEquals(1,count($rows->Rows));
        $this->assertStringContainsString("user_id not in (2,3)",$formDataSource->LastQuery);
        $this->assertEquals($rows->Rows[0]->Reference,'60350df06d810ed76683594a18ffce545f025d874');
    }

    public function testHasRoleWorks(){
        $pageOptions=new RNPageOptionsDTO();
        $pageOptions->DataSources=[];

        $condition=$this->GenerateCondition(100);
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__entryid','201','Equal');
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__userId',['administrator','editor'],'HasRole','User');

        $pageBuilderOptions=new PageBuilderOptionsDTO();
        $pageBuilderOptions->Page=$pageOptions;
        $pageBuilder=new PageBuilderGenerator(new MockLoader(),$pageBuilderOptions);
        $formDataSource=new FormDataSource($pageBuilder,$condition);
        $pageOptions->DataSources[]=$formDataSource;

        $rows=$formDataSource->GetRows();
        $this->assertEquals(1,count($rows->Rows));
        $this->assertStringContainsString("capabilities' and (usermeta.meta_value like concat('%','administrator','%')  or usermeta.meta_value like concat('%','editor','%')",$formDataSource->LastQuery);
        $this->assertEquals($rows->Rows[0]->Reference,'60350df06d810ed76683594a18ffce545f025d874');
    }


    public function testHasNotRoleWorks(){
        $pageOptions=new RNPageOptionsDTO();
        $pageOptions->DataSources=[];

        $condition=$this->GenerateCondition(100);
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__entryid','201','Equal');
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__userId',['editor','subscriber'],'HasNotRole','User');

        $pageBuilderOptions=new PageBuilderOptionsDTO();
        $pageBuilderOptions->Page=$pageOptions;
        $pageBuilder=new PageBuilderGenerator(new MockLoader(),$pageBuilderOptions);
        $formDataSource=new FormDataSource($pageBuilder,$condition);
        $pageOptions->DataSources[]=$formDataSource;

        $rows=$formDataSource->GetRows();
        $this->assertEquals(1,count($rows->Rows));
        $this->assertStringContainsString("and (usermeta.meta_value not like concat('%','editor','%')  and usermeta.meta_value not like concat('%','subscriber','%')",$formDataSource->LastQuery);
        $this->assertEquals($rows->Rows[0]->Reference,'60350df06d810ed76683594a18ffce545f025d874');
    }

    public function testCurrentLoggedInUserWorks(){
        $pageOptions=new RNPageOptionsDTO();
        $pageOptions->DataSources=[];

        $condition=$this->GenerateCondition(100);
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__entryid','201','Equal');
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__userId','',"UserViewingThePage",'User');

        $pageBuilderOptions=new PageBuilderOptionsDTO();
        $pageBuilderOptions->Page=$pageOptions;
        $pageBuilder=new PageBuilderGenerator(new MockLoader(),$pageBuilderOptions);
        $formDataSource=new FormDataSource($pageBuilder,$condition);
        $pageOptions->DataSources[]=$formDataSource;

        $rows=$formDataSource->GetRows();
        $this->assertEquals(1,count($rows->Rows));
        $this->assertStringContainsString("user_id = 1",$formDataSource->LastQuery);
        $this->assertEquals($rows->Rows[0]->Reference,'60350df06d810ed76683594a18ffce545f025d874');
    }

    public function testCurrentLoggedInUserWithGuestReturnNoRowsWorks(){
        $pageOptions=new RNPageOptionsDTO();
        $pageOptions->DataSources=[];

        $condition=$this->GenerateCondition(100);
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__entryid','201','Equal');
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__userId','',"UserViewingThePage",'User');

        $loader=new MockLoader();
        $pageBuilderOptions=new PageBuilderOptionsDTO();
        $pageBuilderOptions->Page=$pageOptions;
        $pageBuilder=new PageBuilderGenerator($loader,$pageBuilderOptions);
        $loader->GetUserIntegration()->SetCurrentUserId(0);
        $formDataSource=new FormDataSource($pageBuilder,$condition);
        $pageOptions->DataSources[]=$formDataSource;

        $rows=$formDataSource->GetRows();
        $this->assertEquals(0,count($rows->Rows));
        $this->assertStringContainsString("and false",$formDataSource->LastQuery);
    }
//#endregion


//#region "User Comparisons"
    public function testEntryIdComparison(){
        $pageOptions=new RNPageOptionsDTO();
        $pageOptions->DataSources=[];

        $condition=$this->GenerateCondition(100);
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__entryid','201','Equal','EntryId');
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__entryid','202','NotEqual','EntryId');
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__entryid','200','GreaterThan','EntryId');
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__entryid','200','GreaterOrEqualThan','EntryId');
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__entryid','202','LessThan','EntryId');
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__entryid','202','LessOrEqualThan','EntryId');


        $loader=new MockLoader();
        $pageBuilderOptions=new PageBuilderOptionsDTO();
        $pageBuilderOptions->Page=$pageOptions;
        $pageBuilder=new PageBuilderGenerator($loader,$pageBuilderOptions);
        $formDataSource=new FormDataSource($pageBuilder,$condition);
        $pageOptions->DataSources[]=$formDataSource;

        $rows=$formDataSource->GetRows();
        $this->assertEquals(1,count($rows->Rows));
        $this->assertStringContainsString("id = 201",$formDataSource->LastQuery);
        $this->assertStringContainsString("id <> 202 or ROOT.id is null",$formDataSource->LastQuery);
        $this->assertStringContainsString("id > 200",$formDataSource->LastQuery);
        $this->assertStringContainsString("id >= 200",$formDataSource->LastQuery);
        $this->assertStringContainsString("id < 202",$formDataSource->LastQuery);
        $this->assertStringContainsString("id <= 202",$formDataSource->LastQuery);
    }


    public function testLastXEntriesWork(){
        $pageOptions=new RNPageOptionsDTO();
        $pageOptions->DataSources=[];

        $condition=$this->GenerateCondition(100);
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__entryid','2',"LastXEntries",'EntryId');


        $loader=new MockLoader();
        $pageBuilderOptions=new PageBuilderOptionsDTO();
        $pageBuilderOptions->Page=$pageOptions;
        $pageBuilder=new PageBuilderGenerator($loader,$pageBuilderOptions);

        $formDataSource=new FormDataSource($pageBuilder,$condition);
        $pageOptions->DataSources[]=$formDataSource;

        $rows=$formDataSource->GetRows();
        $this->assertEquals(2,count($rows->Rows));
        $this->assertStringContainsString("select id from wptests_rnpagebuilder_records records order by date limit 0,2",$formDataSource->LastQuery);

    }

    public function testLastXEntriesByUserWork(){
        $pageOptions=new RNPageOptionsDTO();
        $pageOptions->DataSources=[];

        $condition=$this->GenerateCondition(100);
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__entryid','2',"LastXEntriesByUser",'EntryId');


        $loader=new MockLoader();
        $pageBuilderOptions=new PageBuilderOptionsDTO();
        $pageBuilderOptions->Page=$pageOptions;
        $pageBuilder=new PageBuilderGenerator($loader,$pageBuilderOptions);

        $formDataSource=new FormDataSource($pageBuilder,$condition);
        $pageOptions->DataSources[]=$formDataSource;

        $rows=$formDataSource->GetRows();
        $this->assertEquals(2,count($rows->Rows));
        $this->assertStringContainsString("select id from wptests_rnpagebuilder_records records order by date limit 0,2",$formDataSource->LastQuery);
        $this->assertStringContainsString(".user_id = 1",$formDataSource->LastQuery);

    }



    public function testLastXEntriesByUserReturnNoRecordsForGuestWork(){
        $pageOptions=new RNPageOptionsDTO();
        $pageOptions->DataSources=[];

        $condition=$this->GenerateCondition(100);
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__entryid','2',"LastXEntriesByUser",'EntryId');


        $loader=new MockLoader();
        $loader->GetUserIntegration()->SetCurrentUserId(0);
        $pageBuilderOptions=new PageBuilderOptionsDTO();
        $pageBuilderOptions->Page=$pageOptions;
        $pageBuilder=new PageBuilderGenerator($loader,$pageBuilderOptions);

        $formDataSource=new FormDataSource($pageBuilder,$condition);
        $pageOptions->DataSources[]=$formDataSource;

        $rows=$formDataSource->GetRows();
        $this->assertEquals(0,count($rows->Rows));
        $this->assertStringContainsString("and (false )",$formDataSource->LastQuery);

    }

    public function testCanLoadUserName(){
        $pageOptions=new RNPageOptionsDTO();
        $pageOptions->DataSources=[];

        $condition=$this->GenerateCondition(100);
        $condition->Condition->ConditionGroups[0]->ConditionLines[]=$this->GenerateConditionLine('__entryid','2',"LastXEntriesByUser",'EntryId');


        $loader=new MockLoader();
        $pageBuilderOptions=new PageBuilderOptionsDTO();
        $pageBuilderOptions->Page=$pageOptions;
        $pageBuilder=new PageBuilderGenerator($loader,$pageBuilderOptions);

        $formDataSource=new FormDataSource($pageBuilder,$condition);
        $formDataSource->Options->FieldsUsed[]='__userId';
        $pageOptions->DataSources[]=$formDataSource;

        $ds=$formDataSource->GetRows();

        $this->assertEquals('admin',$ds->Rows[0]->f___userId->DisplayName);
    }
//#endregion

}

