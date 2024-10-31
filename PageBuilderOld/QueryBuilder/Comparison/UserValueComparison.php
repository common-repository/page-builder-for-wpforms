<?php


namespace rnpagebuilder\PageBuilderOld\QueryBuilder\Comparison;


use rnpagebuilder\core\Exception\ExceptionSeverity;
use rnpagebuilder\core\Exception\FriendlyException;
use rnpagebuilder\PageBuilderOld\QueryBuilder\Comparison\ComparisonFormatter\ComparisonFormatterBase;
use rnpagebuilder\PageBuilderOld\QueryBuilder\Comparison\ComparisonFormatter\StringComparisonFormatter;
use rnpagebuilder\PageBuilderOld\QueryBuilder\Filters\FilterLineBase;
use rnpagebuilder\PageBuilderOld\QueryBuilder\QueryElement\Dependency;
use rnpagebuilder\Utilities\Sanitizer;

class UserValueComparison extends ComparisonBase
{
    public $Table;
    public $Column;
    public $Value;
    /** @var ComparisonFormatterBase */
    public $ComparisonFormatter;
    /** @var FilterLineBase */
    public $FilterLine;

    /**
     * FixedValueComparison constructor.
     * @param $FilterLine
     * @param $Table
     * @param $Column
     * @param $Comparison
     * @param $Value
     * @param null $ComparisonFormatter ComparisonFormatterBase
     */
    public function __construct($FilterLine,$Table, $Column, $Comparison, $Value,$ComparisonFormatter=null)
    {
        $this->FilterLine=$FilterLine;
        $this->Table = $Table;
        $this->Column = $Column;
        $this->Comparison = $Comparison;
        $this->Value = $Value;
        $this->ComparisonFormatter=$ComparisonFormatter;

        if($this->ComparisonFormatter==null)
            $this->ComparisonFormatter=new StringComparisonFormatter();
    }


    public function CreateComparison()
    {
        return $this->CreateComparisonString($this->Table.'.'.$this->Column,$this->ComparisonFormatter->Format($this->Value));
    }

    public function CreateComparisonString($leftSide, $rightSide)
    {


        switch ($this->Comparison)
        {
            case "UserViewingThePage":
                $userIntegration=$this->FilterLine->FilterGroup->QueryBuilder->Loader->GetUserIntegration();
                $userid=$userIntegration->GetCurrentUserId();
                if($userid==0)
                    return 'false';

                $comparator=' = ';
                $dbmanager=$this->FilterLine->FilterGroup->QueryBuilder->Loader->GetDBManager();


                return $leftSide.$comparator.$dbmanager->EscapeNumber($userid);
            case 'UserIs':
            case 'UserIsNot':

                $usersToCompare=Sanitizer::SanitizeArray($this->Value);
                $userList=[];
                global $wpdb;
                foreach($usersToCompare as $currentUser)
                    $userList[]=$wpdb->prepare('%d',$currentUser);

                if(count($userList)==0)
                {
                    return 'false';
                }

                $comparator=' in ';
                if($this->Comparison=='UserIsNot')
                    $comparator=' not in ';

                return $leftSide.$comparator.'('.implode(',',$userList).')';
            case 'HasRole':
            case 'HasNotRole':
                global $wpdb;

                $userMetaDependency=new Dependency($wpdb->usermeta,'usermeta');
                $userMetaDependency->Comparisons[]=new ColumnComparison('ROOT','user_id','Equal','usermeta','user_id');
                $userMetaDependency->Comparisons[]=new FixedValueComparison('usermeta','meta_key','Equal',$wpdb->prefix.'capabilities');

               if(!$this->FilterLine->HasDependency($userMetaDependency))
                    $this->FilterLine->Dependencies[]=$userMetaDependency;

                $comparisonType=null;
                $comparison=null;
                if($this->Comparison=='HasRole')
                {
                    $comparison = new OrValueComparison();
                    $comparisonType='Contains';
                }
                else
                {
                    $comparison = new AndValueComparison();
                    $comparisonType='NotContains';
                }

                foreach(Sanitizer::SanitizeArray($this->Value) as $currentRole)
                {
                    $comparison->AddComparison(new FixedValueComparison('usermeta','meta_value',$comparisonType,Sanitizer::SanitizeString($currentRole)));
                }


                return $comparison->CreateComparison();

            default:
                throw new FriendlyException('Invalid comparison type '.$this->Comparison,ExceptionSeverity::$FATAL);


        }
    }


}