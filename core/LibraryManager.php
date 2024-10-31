<?php

namespace rnpagebuilder\core;


class LibraryManager
{
    /** @var Loader */
    public $Loader;

    public $dependencies = [];

    public function __construct($loader)
    {
        $this->Loader = $loader;
    }

    public static function GetInstance(){
        return new LibraryManager(apply_filters('allinoneforms_get_loader',null));
    }

    public function GetDependencyHooks(){
        $hooks=[];
        foreach($this->dependencies as $currentDependency)
        {
            $hooks[]=\str_replace('@',$this->Loader->Prefix.'_',$currentDependency);
        }
        return $hooks;
    }

    public function AddHTMLGenerator(){
        self::AddLit();
        $this->Loader->AddScript('htmlgenerator','js/dist/RNPBHTMLGenerator_bundle.js',array('@pageBuilder','@lit'));

    }

    public function AddDropdownButton(){
        self::AddLit();
        self::AddCore();
        $this->Loader->AddScript('dropdownbutton','js/dist/RNPBDropDownButton_bundle.js',array('@Core'));
        $this->Loader->AddStyle('dropdownbutton','js/dist/RNPBDropDownButton_bundle.css');

        $this->AddDependency('@dropdownbutton');

    }

    public function AddConditionDesigner()
    {

        self::AddLit();
        self::AddFormBuilderCore();
        $this->Loader->AddScript('conditiondesigner','js/dist/RNPBConditionDesigner_bundle.js',array('@lit','@FormBuilderCore'));
        $this->Loader->AddStyle('conditiondesigner','js/dist/RNPBConditionDesigner_bundle.css');
        $this->AddDependency('@conditiondesigner');

        $userIntegration=new UserIntegration($this->Loader);
        $this->Loader->LocalizeScript('rnConditionDesignerVar','conditiondesigner','alloinoneforms_list_users',[
            "Roles"=>$userIntegration->GetRoles()
        ]);
    }

    public function AddTooltip(){
        self::AddLit();
        self::AddFormBuilderCore();
        $this->Loader->AddScript('tooltip','js/dist/RNPBTooltip_bundle.js',array('@lit','@Core'));
        $this->Loader->AddStyle('tooltip','js/dist/RNPBTooltip_bundle.css');
    }
    private function AddDependency($dependency)
    {
        if (!in_array($dependency, $this->dependencies))
            $this->dependencies[] = $dependency;
    }

    public function AddConditionalFieldSet(){
        self::AddSwitchContainer();
        $this->Loader->AddScript('conditionalfieldset','js/dist/RNPBConditionalFieldSet_bundle.js',array('@switchcontainer'));
        $this->AddDependency('@conditionalfieldset');
    }

    public function AddSingleLineGenerator()
    {
        $this->Loader->AddScript('singlelinegenerator','js/dist/RNPBSingleLineGenerator_bundle.js');
        $this->AddDependency('@singlelinegenerator');

    }

    public function AddSwitchContainer(){
        self::AddLit();
        $this->Loader->AddScript('switchcontainer','js/dist/RNPBSwitchContainer_bundle.js',array('@lit'));
        $this->AddDependency('@switchcontainer');

    }

    public function AddChart(){
        $this->Loader->AddScript('chart','js/lib/chart/chart.js');
        $this->Loader->AddScript('palette','js/lib/chart/palette.js');
    }

    public function AddInputs(){
        self::AddLit();
        self::AddCore();
        self::AddSelect();
        $this->Loader->AddScript('date','js/lib/date/flatpickr.js',array('@lit'));
        $this->Loader->AddStyle('date','js/lib/date/flatpickr.min.css');
        $this->Loader->AddScript('inputs','js/dist/RNPBInputs_bundle.js',array('@lit','@select','@date'));
        $this->Loader->AddStyle('inputs','js/dist/RNPBInputs_bundle.css');

        $this->AddDependency('@inputs');

    }

    public function AddAlertDialog(){
        self::AddLit();
        self::AddCore();
        self::AddDialog();
        $this->Loader->AddScript('AlertDialog','js/dist/RNPBAlertDialog_bundle.js',array('@lit','@Dialog','@Core'));
        $this->Loader->AddStyle('AlertDialog','js/dist/RNPBAlertDialog_bundle.css');
        $this->AddDependency('@AlertDialog');

    }

    public function AddTextEditor(){
        self::AddLit();
        self::AddDialog();
        self::AddInputs();
        self::AddAccordion();
        $this->Loader->AddScript('texteditor','js/dist/RNPBTextEditor_bundle.js',array('@lit','@Dialog','@inputs'));
        $this->Loader->AddStyle('texteditor','js/dist/RNPBTextEditor_bundle.css');
        $this->AddDependency('@texteditor');

    }
    public function AddCore(){
        self::AddLoader();
        self::AddLit();
        $this->Loader->AddScript('Core', 'js/dist/RNPBCore_bundle.js', array('@loader', '@lit'));
        $this->AddDependency('@Core');
    }

    public function AddFormulas(){
        self::AddFormBuilderCore();
        $this->Loader->AddScript('Formula','js/dist/RNPBFormulaCore_bundle.js',array('@FormBuilderCore'));
        $this->AddDependency('@Formula');
    }



    public function AddFormBuilderCore(){
        self::AddCore();
        self::AddDialog();

    }

    public function AddLoader()
    {
        $this->Loader->AddScript('loader', 'js/lib/loader.js');
        $this->AddDependency('@loader');
    }

    public function AddSelect(){
        $this->Loader->AddScript('select','js/lib/tomselect/js/tom-select.complete.js');
        $this->Loader->AddStyle('select','js/lib/tomselect/css/tom-select.bootstrap5.css');
        $this->AddDependency('@select');
    }

    public function AddCarousel(){
        $this->Loader->AddScript('carousel','js/lib/Swiper/swiper-bundle.min.js');
    }


    public function AddLit()
    {
        self::AddLoader();
        $this->Loader->AddScript('lit', 'js/dist/RNPBLit_bundle.js', array('@loader'));
        $this->AddDependency('@lit');
    }

    public function AddCoreUI()
    {
        self::AddCore();
        $this->Loader->AddScript('CoreUI', 'js/dist/RNPBCoreUI_bundle.js', array('@Core'));
        $this->Loader->AddStyle('CoreUI', 'js/dist/RNPBCoreUI_bundle.css');

        $this->AddDependency('@CoreUI');
    }

    public function AddTranslator($fileList)
    {
        $this->Loader->AddRNTranslator($fileList);
        $this->AddDependency('@RNTranslator');
    }

    public function AddDialog()
    {
        self::AddLit();
        self::AddCore();
        $this->Loader->AddScript('Dialog', 'js/dist/RNPBDialog_bundle.js', array('@lit','@Core'));
        $this->Loader->AddStyle('Dialog', 'js/dist/RNPBDialog_bundle.css');
        $this->AddDependency('@Dialog');
    }

    public function AddContext(){
        self::AddLit();
        $this->Loader->AddScript('Context','js/dist/RNPBContext_bundle.js');
        $this->Loader->AddStyle('Context','js/dist/RNPBContext_bundle.css');
    }

    public function AddPreMadeDialog(){
        self::AddDialog();
        self::AddSpinner();
        $this->Loader->AddScript('PreMadeDialog', 'js/dist/RNPBPreMadeDialogs_bundle.js', array('@Dialog'));

    }

    public function AddContextMenu(){
        self::AddLit();
        $this->Loader->AddScript('ContextMenu','js/dist/RNPBContextMenu_bundle.js',array('@lit'));
        $this->Loader->AddStyle('ContextMenu','js/dist/RNPBContextMenu_bundle.css');
        $this->AddDependency('@ContextMenu');
    }

    public function AddDate(){
        self::AddLit();;
        $this->Loader->AddScript('date','js/lib/date/flatpickr.js',array('@lit'));
        $this->Loader->AddStyle('date','js/lib/date/flatpickr.min.css');
        $this->AddDependency('@date');
    }

    public function AddAccordion()
    {
        self::AddLit();
        $this->Loader->AddScript('Accordion', 'js/dist/RNPBAccordion_bundle.js', array('@lit'));
        $this->Loader->AddStyle('Accordion', 'js/dist/RNPBAccordion_bundle.css');
        $this->AddDependency('@Accordion');
    }


    public function AddTabs()
    {
        $this->AddLit();
        $this->Loader->AddScript('Tabs', 'js/dist/RNPBTabs_bundle.js', array('@lit'));
        $this->Loader->AddStyle('Tabs', 'js/dist/RNPBTabs_bundle.css');

        $this->AddDependency('@Tabs');
    }

    public function AddCalendar(){
        $this->Loader->AddScript('calendar','js/lib/calendar/main.js');
        $this->Loader->AddStyle('calendar','js/lib/calendar/main.css');
        $this->AddDependency('@calendar');
    }

    public function AddSpinner(){
        self::AddLit();
        self::AddCore();
        $this->Loader->AddScript('Spinner', 'js/dist/RNPBSpinnerButton_bundle.js', array('@lit','@Core'));
        $this->Loader->AddStyle('Spinner', 'js/dist/RNPBSpinnerButton_bundle.css');
    }

    public function AddWPTable()
    {
        self::AddCore();
        $this->Loader->AddScript('WPTable', 'js/dist/RNPBWPTable_bundle.js', array('@Core'));
        $this->Loader->AddStyle('WPTable', 'js/dist/RNPBWPTable_bundle.css');
        $this->AddDependency('@WPTable');
    }

    public function AddPageBuilder()
    {
    //    $this->AddPage();
        $this->AddDialog();
        //$this->AddHTMLRenderer();
        $this->AddContextMenu();
        //$this->AddGridFieldBlock();
        //$this->AddFormFieldBlock();
        //$this->AddTextFieldBlock();
        //$this->AddNavigatorFieldBlock();
        //$this->AddSearchBarBlock();
        //$this->AddFormBlock();;
        $this->AddPreMadeDialog();
        //$this->AddCalendarBlock();
        //$this->AddImageBlock();
        //$this->AddListBlock();
        $this->AddTabs();
        $this->AddAccordion();
        $this->AddInputs();
        $this->AddHTMLGenerator();
        $this->Loader->AddScript('pageBuilder','js/dist/RNPBPageBuilder_bundle.js',$this->GetDependencyHooks());
        $this->Loader->AddStyle('pageBuilder','js/dist/RNPBPageBuilder_bundle.css');
        $this->AddDependency('@pageBuilder');

    }

    public function AddFormBlock(){
        $this->Loader->AddScript('formblock','js/dist/RNPBFormBlock_bundle.js');
        $this->AddDependency('@formblock');
    }

    public function AddCalendarBlock(){
        $this->Loader->AddScript('calendarblock','js/dist/RNPBCalendarBlock_bundle.js');
        $this->AddDependency('@calendarblock');
    }

    public function AddListBlock(){
        $this->Loader->AddScript('listblock','js/dist/RNPBListBlock_bundle.js');
        $this->AddDependency('@listblock');
    }

    public function AddImageBlock(){
        $this->Loader->AddScript('imageblock','js/dist/RNPBImageBlock_bundle.js');
        $this->AddDependency('@imageblock');
    }

    public function AddSearchBarBlock(){
        $this->Loader->AddScript('searchbarblock','js/dist/RNPBSearchBarBlock_bundle.js');
        $this->AddDependency('@searchbarblock');
    }

    public function AddHTMLRenderer()
    {
        $this->Loader->AddScript('htmlrenderer','js/dist/RNPBHtmlRenderer_bundle.js');
        $this->AddDependency('@htmlrenderer');
    }

    public function AddFormFieldBlock(){
        $this->Loader->AddScript('formfieldblock','js/dist/RNPBFormFieldBlock_bundle.js');
        $this->Loader->AddStyle('formfieldblock','js/dist/RNPBFormFieldBlock_bundle.css');
        $this->AddDependency('@formfieldblock');
    }

    public function AddNavigatorFieldBlock(){

        $this->Loader->AddScript('navigatorfieldblock','js/dist/RNPBNavigatorFieldBlock_bundle.js');
        $this->Loader->AddStyle('navigatorfieldblock','js/dist/RNPBNavigatorFieldBlock_bundle.css');
        $this->AddDependency('@navigatorfieldblock');
    }

    public function AddTextFieldBlock(){
        $this->Loader->AddScript('textfieldblock','js/dist/RNPBTextFieldBlock_bundle.js');
        $this->Loader->AddStyle('textfieldblock','js/dist/RNPBTextFieldBlock_bundle.css');
        $this->AddDependency('@textfieldblock');
    }

    public function AddGridFieldBlock(){
        $this->Loader->AddScript('gridfieldblock','js/dist/RNPBGridFieldBlock_bundle.js');
        $this->AddDependency('@gridfieldblock');
    }


    private function AddPage()
    {
        $this->Loader->AddScript('page','js/dist/RNPBRNPage_bundle.js');
        $this->Loader->AddStyle('page','js/dist/RNPBRNPage_bundle.css');
        $this->AddDependency('@page');
    }
}