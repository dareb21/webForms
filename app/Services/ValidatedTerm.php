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
            $newTerm = Term::create([
                "term" => $thisTerm,
            ]);

            return ["newTerm"=>$thisTerm,"newTermId"=>$newTerm->id,"exists"=>false];
         }
         
    }
}