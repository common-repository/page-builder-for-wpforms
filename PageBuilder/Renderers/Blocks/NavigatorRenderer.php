<?php


namespace rnpagebuilder\PageBuilder\Renderers\Blocks;


use rnpagebuilder\DTO\FormFieldBlockOptionsDTO;
use rnpagebuilder\DTO\NavigatorFieldBlockOptionsDTO;
use rnpagebuilder\DTO\RNBlockBaseOptionsDTO;
use rnpagebuilder\DTO\RunnableNavigatorOptionsDTO;
use rnpagebuilder\DTO\TextFieldBlockOptionsDTO;
use rnpagebuilder\PageBuilder\Renderers\Blocks\Core\BlockRendererBase;
use rnpagebuilder\PageBuilder\Renderers\ColumnRenderer;
use rnpagebuilder\PageBuilder\Renderers\Core\RendererBase;
use rnpagebuilder\SlateGenerator\SlateTextGenerator\SlateTextGenerator;
use Twig\Markup;

class NavigatorRenderer extends BlockRendererBase
{
    /** @var NavigatorFieldBlockOptionsDTO */
    public $Options;
    public $NextIndex;
    public $PreviousIndex;

    public function __construct(ColumnRenderer $columnRenderer, RNBlockBaseOptionsDTO $options,$dataSource)
    {
        parent::__construct($columnRenderer, $options,$dataSource);


    }

    public function InitializeWithDataSource()
    {
        $currentIndex=$this->GetPageRenderer()->PageGenerator->PageIndex;
        $dataSource=$this->GetDefaultDataSource();
        $previousIndex=$currentIndex-1;
        if($previousIndex==0)
            $this->PreviousIndex=-1;


        $this->PreviousIndex=$previousIndex;
        if($dataSource->Options->InitialNeededRows!=0&&$this->GetDefaultDataSource()->Count>($currentIndex)*$this->GetPageRenderer()->GetPageSize())
            $this->NextIndex=$currentIndex+1;
        else
            $this->NextIndex=-1;

    }

    public function GetTotalNumberOfPages(){
        if($this->GetPageRenderer()->PageGenerator->PageSize==0)
            return 1;
        $ds=$this->GetPageRenderer()->GetDefaultDataSource();
        if($ds==null)
            return 0;


        return  ceil($ds->Count/$this->GetPageRenderer()->PageGenerator->PageSize);
    }

    protected function GetTemplateName()
    {
        return 'Blocks/NavigatorRenderer.twig';
    }

    public function GetLabel(){
        if($this->Options->LabelType=='RowCount')
        {
            $dataSource=$this->GetDefaultDataSource();
            if($dataSource==null)
                return __('No records found');

            $size=$this->GetPageRenderer()->GetPageSize();
            return ($dataSource->Count==0?0: ($this->GetPageRenderer()->PageGenerator->PageIndex)).'/'. ($size==0?0:$this->GetTotalNumberOfPages());


        }else{
            $slate=new SlateTextGenerator($this->loader,$this->GetPageRenderer());
            return $slate->GetText($this->Options->Text);
        }
    }


}

