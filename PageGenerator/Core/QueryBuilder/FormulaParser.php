<?php

namespace rnpagebuilder\PageGenerator\Core\QueryBuilder;

use rnpagebuilder\PageGenerator\Core\QueryBuilder\EntryRetriever\EntryRetriever;

class FormulaParser
{
    /**
     * @param $value
     * @param $formulaId
     * @param $entryRetriever EntryRetriever
     * @return void
     */
    public function Parse($value,$formulaId,$entryRetriever)
    {
        foreach($entryRetriever->PageGenerator->Options->Formulas as $currentFormula)
        {
            if($currentFormula->Id!=$formulaId)
                continue;

            if($currentFormula->FieldToUse=='')
                return $value;

            foreach($entryRetriever->FieldSettings as $currentField)
            {
                if($currentField->Id==$currentFormula->FieldToUse)
                {
                    if(in_array($currentField->Type,['Currency','CurrencyMultiple']))
                    {
                        if(function_exists('wpforms_format_amount'))
                            return html_entity_decode(wpforms_format_amount($value,true));
                        return $value;


                    }
                    return $value;

                }
            }


        }
        return $value;
    }
}