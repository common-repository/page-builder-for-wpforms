<?php


namespace rnpagebuilder\PageBuilder\Renderers\Blocks\GridRenderer;


use rnpagebuilder\core\Utils\ObjectSanitizer;
use rnpagebuilder\DTO\FormFieldBlockOptionsDTO;
use rnpagebuilder\DTO\GridColumnOptionsDTO;
use rnpagebuilder\DTO\GridFieldBlockOptionsDTO;
use rnpagebuilder\DTO\RNBlockBaseOptionsDTO;
use rnpagebuilder\DTO\RunnableGridOptionsDTO;
use rnpagebuilder\DTO\RunnableNavigatorOptionsDTO;
use rnpagebuilder\DTO\TextFieldBlockOptionsDTO;
use rnpagebuilder\PageBuilder\DataSources\FormDataSource\FormDataSource;
use rnpagebuilder\PageBuilder\Renderers\Blocks\Core\BlockRendererBase;
use rnpagebuilder\PageBuilder\Renderers\ColumnRenderer;
use rnpagebuilder\PageBuilder\Renderers\Core\RendererBase;
use rnpagebuilder\SlateGenerator\SlateTextGenerator\SlateTextGenerator;
use Twig\Markup;

class GridRenderer extends BlockRendererBase
{
    /** @var GridFieldBlockOptionsDTO */
    public $Options;
    public $Rows;

    public function __construct(ColumnRenderer $columnRenderer, GridFieldBlockOptionsDTO $options,$dataSource)
    {
        parent::__construct($columnRenderer, $options,$dataSource);
        $this->AddScript('grid','js/dist/RNMainRunnableGrid_bundle.js');
    }

    public function InitializeWithDataSource()
    {
        $ds=$this->GetDefaultDataSource();
        $this->Rows=[];
        if($ds==null)
            return;


        foreach($ds->Rows as $currentRow)
        {
            $this->Rows[]=new GridRowRenderer($this,$currentRow);

        }
    }


    public function GetCurrentPage(){
        return $this->GetPageRenderer()->PageGenerator->PageIndex;
    }

    public function GetTotalNumberOfPages(){
        if($this->GetPageRenderer()->PageGenerator->PageSize==0)
            return 1;
        $ds=$this->GetPageRenderer()->GetDefaultDataSource();
        if($ds==null)
            return 0;


        return  ceil($ds->Count/$this->GetPageRenderer()->PageGenerator->PageSize);
    }

    public function GetNextIndex()
    {
        if($this->GetCurrentPage()==$this->GetTotalNumberOfPages())
            return -1;

        return $this->GetCurrentPage()+1;
    }

    public function GetPreviousIndex()
    {
        if($this->GetCurrentPage()==1)
            return -1;

        return $this->GetCurrentPage()-1;
    }

    protected function GetTemplateName()
    {
        return 'Blocks/GridRenderer/GridRenderer.twig';
    }

    /**
     * @param $column GridColumnOptionsDTO
     */
    public function CreateSortableHeader($column){

        $field=ObjectSanitizer::Sanitize($column->Content,(object)array(
            'FieldId'=>'',
            'PathId'=>'',
            'Path'=>array('')
        ));
        /** @var FormDataSource $form */
        $form=$this->GetDefaultDataSource();

        $direction=$form->GetSortItemDirection($field->FieldId,$field->PathId);

        $actions='javascript:RNSubmitAction(event,\'sort\',\''.$field->FieldId.'\',true);RNSubmitAction(event,\'path\',\''.$field->PathId.'\',true);';
        if($direction==null)
        {
            return new Markup('<div class="direction desc"><a href="#" onclick="'.$actions.'RNSubmitAction(event,\'ori\',\'desc\');">'.esc_html($column->Header).'</a></div>','UTF-8');
        }

        if($direction=='desc')
        {
            return new Markup('<div class="direction desc"><a href="#" onclick="'.$actions.'RNSubmitAction(event,\'ori\',\'asc\')">'.esc_html($column->Header).'</a><span style="height: 15px">'.file_get_contents($this->loader->DIR.'icons/caret-down.svg').'</span></div>','UTF-8');
        }else{
            return new Markup('<div class="direction asc"><a href="#" onclick="'.$actions.'RNSubmitAction(event,\'ori\',\'desc\')">'.esc_html($column->Header).'</a><span style="height: 15px">'.file_get_contents($this->loader->DIR.'icons/caret-up.svg').'</span></div>','UTF-8');
        }
    }

    public function GetLabel(){
        return '';
    }

    public function GetValue(){
        return '';
    }


    public function InternalGetOptions()
    {

    }

}

