<?php


namespace rnpagebuilder\Utilities\ServerActions\GoToPage;



use rnpagebuilder\core\db\PageRepository;
use rnpagebuilder\core\Exception\FriendlyException;
use rnpagebuilder\PageBuilderOld\DataSources\FormDataSource\FormDataSource;
use rnpagebuilder\PageBuilderOld\DataSources\FormDataSource\FormRow;
use rnpagebuilder\PageBuilderOld\PageBuilderGenerator;
use rnpagebuilder\Utilities\ServerActions\Core\ServerActionBase;

class GoToServerAction extends ServerActionBase
{
    public $PageId;
    /** @var GoToServerMappings[] */
    public $Mappings;
    public function LoadDefaultValues()
    {
        parent::LoadDefaultValues();
        $this->PageId=0;
        $this->Name='GoToPage';
        $this->Mappings=[];
        $this->AddType('Mappings','Utilities\ServerActions\GoToPage\GoToServerMappings');
    }

    /**
     * @param $row FormRow
     */
    public function Initialize($row)
    {
        foreach($this->Mappings as $currentMapping)
        {
            $currentMapping->Value=$row->GetStringValue($currentMapping->MappedToFieldId,$currentMapping->MappedToPathId);
        }

    }

    public function Register($loader)
    {
        parent::Register($loader);
        add_filter('pagebuilder_before_loading_page',array($this,'PageLoaded'),10,3);
    }

    public function PageLoaded($generatorToUse,$page,$postItems)
    {
        $repository=new PageRepository($this->Loader);
        $options=$repository->GetPageById($this->PageId);

        if($options==null)
        {
            throw new FriendlyException('Invalid page');
        }

        $generator = new PageBuilderGenerator($this->Loader, $options, $postItems);

        /** @var FormDataSource $formDataSource */
        $formDataSource=$generator->DataSources[0];
        if($formDataSource->Options->ParameterCondition==null)
            return $generator;
        foreach($formDataSource->Options->ParameterCondition->ConditionGroups as $group)
        {
            foreach($group->ConditionLines as $currentLine)
            {
                foreach($this->Mappings as $currentMapping)
                {
                    if($currentMapping->ParameterId==$currentLine->Id)
                        $currentLine->Value=$currentMapping->Value;
                }
            }
        }

        return $generator;
    }
}