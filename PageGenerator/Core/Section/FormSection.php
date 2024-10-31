<?php

namespace rnpagebuilder\PageGenerator\Core\Section;

use rnpagebuilder\pr\Managers\EntryFiller\EntryFiller;
use Twig\Markup;

class FormSection extends PageSectionBase
{
    /** @var EntryFiller */
    public $EntryFiller;
    public function __construct($area, $sectionOptions)
    {
        parent::__construct($area, $sectionOptions);

    }

    public function Render()
    {
        $this->EntryFiller=new EntryFiller($this->Area);
        if($this->GetPageGenerator()->EntryRetriever->GetCurrentRow()==null)
        {
            echo __("Entry not found","rnpagebuilder");
            return;
        }
        add_filter( 'wpforms_field_properties', [ $this, 'LoadFieldProperties' ], 10, 3 );
        $html= new Markup(do_shortcode( '[wpforms id="' . absint( $this->GetPageGenerator()->Options->FormId ) . '"]' ),"UTF-8");
        remove_filter( 'wpforms_field_properties', [ $this, 'LoadFieldProperties' ]);
        $this->EntryFiller->Destroy();
        return $html;

    }

    public function LoadFieldProperties($properties, $field, $form_data){
        $row=$this->GetPageGenerator()->EntryRetriever->GetCurrentRow();
        if($row==null)
            return '';

        $entry_data =wpforms_decode($row->Data->fields);
        $id         = (int) ! empty( $field['id'] ) ? $field['id'] : 0;

        if ( ! isset( $entry_data[ $id ] ) ) {
            return $properties;
        }


        return $this->EntryFiller->FillEntry( $properties, $field, $entry_data );
    }

}