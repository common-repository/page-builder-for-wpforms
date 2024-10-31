<?php


namespace rnpagebuilder\PageBuilderOld\Renderers\Blocks\CalendarRenderer;


use rnpagebuilder\core\LibraryManager;
use rnpagebuilder\DTO\CalendarBlockOptionsDTO;
use rnpagebuilder\DTO\CalendarItemDTO;
use rnpagebuilder\DTO\ConditionGroupOptionsDTO;
use rnpagebuilder\DTO\ConditionLineOptionsDTO;
use rnpagebuilder\DTO\ConditionLineTypeEnumDTO;
use rnpagebuilder\DTO\RunnableCalendarOptionsDTO;
use rnpagebuilder\PageBuilderOld\QueryBuilder\Comparison\DateUnit\DateUnitBase;
use rnpagebuilder\PageBuilderOld\QueryBuilder\Comparison\DateUnit\DayUnit;
use rnpagebuilder\PageBuilderOld\Renderers\Blocks\Core\BlockRendererBase;
use rnpagebuilder\SlateGenerator\SlateHTMLGenerator\SlateHTMLGenerator;
use rnpagebuilder\SlateGenerator\SlateTextGenerator\SlateTextGenerator;
use Twig\Markup;

class CalendarRenderer extends BlockRendererBase
{
    /** @var CalendarItemDTO */
    public $Items;
    /** @var CalendarBlockOptionsDTO */
    public $Options;

    protected function GetTemplateName()
    {
        LibraryManager::AddModal();

        $this->AddScript('pbcalendar','js/lib/calendar/calendar.js');
        $this->AddScript('pbcalendar-datepicker','js/lib/calendar/calendardatepicker.js',array('@pbcalendar'));
        $this->AddScript('pbcalendar-timepicker','js/lib/calendar/calendartimepicker.js',array('@pbcalendar-datepicker'));
        $this->AddScript('pbtcalendar','js/lib/calendar/tcalendar.js',array('@pbcalendar-timepicker'));
        $this->AddScript('runnablecalendar','js/dist/RNMainRunnableCalendar_bundle.js',array('@pbtcalendar','@modal'));



        $this->AddStyle('pbcalendar','js/lib/calendar/calendar.css');



        return 'Blocks/CalendarRenderer/CalendarRenderer.twig';
    }

    public function HasRuntimeOptions()
    {
        return true;
    }

    protected function InternalGetOptions()
    {
        $iterator=$this->GetPageRenderer()->GetDefaultDataSource()->GetIterator();
        $this->Items=[];
        $slate=new SlateTextGenerator($this->loader,$this->GetPageRenderer());
        $toolTipSlate=new SlateHTMLGenerator($this->loader,$this->GetPageRenderer());
        while ($iterator->GetNextRow())
        {
            $item=new CalendarItemDTO();
            $item->Label=strval($slate->GetText($this->Options->ItemLabel,$iterator->GetCurrentRow()));
            $item->StartDate=$this->GetISODate($iterator,$this->Options->StartDateField);
            $item->EndDate=$this->GetISODate($iterator,$this->Options->EndDateField);;
            $item->Tooltip=strval($toolTipSlate->GetHTML($this->Options->Tooltip,$iterator->GetCurrentRow()));
            if($item->StartDate===false||$item->EndDate===false)
                continue;
            $this->Items[]=$item;
        }

        $options=new RunnableCalendarOptionsDTO();
        $options->Mode=$this->Options->Mode;
        $options->Items=$this->Items;

        return $options;
    }

    public function ShouldInsertDateFilters()
    {
        return true;
    }




    public function MaybeUpdateDataSource()
    {
        if(!$this->ShouldInsertDateFilters())
            return;

        $ds=$this->GetDefaultDataSource();
        if($ds==null)
            return;

        $dayUnit=null;
        switch ($this->Options->Mode)
        {
            case 'month':
                $dayUnit=new DayUnit(strtotime('first day of this month'),$this->Options->Mode);
                break;
            case 'week':
                $dayUnit=new DayUnit(strtotime('last sunday'),$this->Options->Mode);
                break;
            case 'day':
                $dayUnit=new DayUnit(strtotime('today'),$this->Options->Mode);
                break;
        }


        $conditionGroup=new ConditionGroupOptionsDTO();
        $conditionGroup->Merge();
        $conditionGroup->ConditionLines=[(new ConditionLineOptionsDTO())->Merge()];
        $conditionGroup->ConditionLines[0]->FieldId=$this->Options->StartDateField;
        $conditionGroup->ConditionLines[0]->Type=ConditionLineTypeEnumDTO::$Standard;
        $conditionGroup->ConditionLines[0]->Value=$dayUnit->GetStartOfUnit();
        $conditionGroup->ConditionLines[0]->Comparison='GreaterOrEqualThan';
        $conditionGroup->ConditionLines[0]->PathId='';
        $conditionGroup->ConditionLines[0]->SubType='Date';


        $conditionGroup->ConditionLines[]=(new ConditionLineOptionsDTO())->Merge();
        $conditionGroup->ConditionLines[1]->FieldId=$this->Options->EndDateField;
        $conditionGroup->ConditionLines[1]->Type=ConditionLineTypeEnumDTO::$Standard;
        $conditionGroup->ConditionLines[1]->Value=$dayUnit->GetEndOfUnit();
        $conditionGroup->ConditionLines[1]->Comparison='LessThan';
        $conditionGroup->ConditionLines[1]->PathId='';
        $conditionGroup->ConditionLines[1]->SubType='Date';

        $ds->AndAdditionalFilters[]=$conditionGroup;


    }

    public function InitializeWithDataSource()
    {
        parent::InitializeWithDataSource(); // TODO: Change the autogenerated stub
    }

    private function GetISODate($iterator, $dateFieldId)
    {
        $value=$iterator->GetCurrentRowValue($dateFieldId);
        if($value==null)
            return null;

        if(!isset($value->Unix))
            return null;

        $unix=$value->Unix;
        $date= date('c',$unix);
        if($date===false)
            return null;
        return $date;
    }


}