<?php
namespace App\Services;
Use App\Models\Term;

Class ValidatedTerm
{
    public function validatedTerm($thisTerm)
    {

        if (empty($thisTerm))
        {
            return ["exists"=>true];
        }

         if (Term::where("term",$thisTerm)->exists())
         {
            return ["exists"=>true];

         }else{
            
            $lastId = Term::latest('id')->value('id');
            $newIdTerm = $lastId ? $lastId + 1 : 1;

            return ["newTerm"=>$thisTerm,"newTermId"=>$newIdTerm,"exists"=>false];
         }
         
    }
}