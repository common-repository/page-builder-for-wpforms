<?php


namespace rnpagebuilder\core\Integration\Adapters\WPForm\EntryEditor;


use rnpagebuilder\core\Exception\FriendlyException;
use rnpagebuilder\core\Integration\Adapters\WPForm\EntryEditor\Fields\Core\WPFormFieldEditorFactory;
use rnpagebuilder\core\Integration\Processors\EntryEditor\EntryEditorBase;
use rnpagebuilder\PageBuilderOld\DataSources\FormDataSource\FormRow;

class WPFormEntryEditor extends EntryEditorBase
{
    public function ExecuteShortCode($formId)
    {
        return do_shortcode('[wpforms id="'.$formId.'"]');
    }


    /**
     * @param $formId
     * @param FormRow $formRow
     * @return string
     */
    public function RenderForm($formId, $formRow = null)
    {


        $formAttributesCallBack=null;

        $propertiesCallBack=null;
        if($formRow!=null)
        {
            $formAttributesCallBack=function ($form_data, $deprecated, $title, $description, $errors)use($formRow){
                $entryId=$formRow->GetStringValue('__entryId');
                echo '<input name="__pbentryid" type="hidden" value="'.esc_attr__($entryId).'"/>';
                echo '<input name="__pbentry_nonce" type="hidden" value="'.esc_attr__(wp_create_nonce($entryId.'_edit')).'"/>';
                return $form_data;
            };

            $propertiesCallBack=function ($properties,$field,$formData) use($formRow) {
                return $this->ProcessField($formRow, $properties,$field,$formData);
            };

            add_action( 'wpforms_frontend_output',$formAttributesCallBack,10,5 );


            add_filter('wpforms_field_properties',$propertiesCallBack,10,3);

        }
        $content=$this->ExecuteShortCode($formId);


        if($formAttributesCallBack!=null)
            remove_action('wpforms_frontend_output',$formAttributesCallBack);
        if($propertiesCallBack!=null)
            remove_filter('wpforms_field_properties',$propertiesCallBack);
        return $content;
    }


    /**
     * @param $formRow FormRow
     * @param $properties
     * @param $field
     * @param $formData
     * @return array
     */
    public function ProcessField($formRow,$properties,$field,$formData)
    {
        $ignoredFields=['pagebreak','divider','html'];
        if(array_search($field['type'],$ignoredFields)!==false)
            return $properties;
        $fieldSettings=$formRow->DataSource->GetFieldById($field['id']);
        if($fieldSettings==null)
            throw new FriendlyException('Invalid field '.$field['id']);

        $editor=WPFormFieldEditorFactory::GetFieldEditor($formRow,$fieldSettings,$properties);
        if($editor==null)
        {
            return $properties;
            throw new FriendlyException('Editor not found for ' . $fieldSettings->SubType);
        }

        return $editor->PrepareProperties($properties);
    }
}