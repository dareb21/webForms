<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\QuestionGroup;
use App\Models\QuestionOption;

class AdminController extends Controller
{

  public function enableEvaluation()
  {
    $survey=Survey::findOrFail(1);
    if (Survey::where("status",1)->count() == 0)
    {
      $survey->status = 1;
      $survey->save();
      return redirect()->route("adminEvaluation");
    }else
    {
      return response()->json("Ya hay una en linea");
    }
    
  }

public function UnableEvaluation()
{
    $survey=Survey::findOrFail(1);
    if( $survey->status == 1)
    {
      $survey->status = 0;
      $survey->save();
      
    }
    return redirect()->route("adminEvaluation");
}



  public function adminDashboard()
    {
        $user = (object) session('google_user');
        return view("admin.adminDashboard",compact("user"));
    }


  public function adminEvaluation()
    {
    $collectionStatus = Collect();
    $surveys = Survey::paginate(10); 
    foreach ($surveys as $survey) {
    $status = $survey->status == 1 ? "Activa" : "Inactiva";
    $collectionStatus->push($status);
    }
    
    return view("admin.adminEvaluation",compact("surveys","collectionStatus"));
    }


    public function adminEvaluationEdit($id)
    {
        // Obtener la encuesta
        $survey = Survey::findOrFail($id);
        
        // Obtener los grupos de preguntas asociados a la encuesta
        $questionGroups = QuestionGroup::where("survey_id", $survey->id)->get();

        // Obtener todas las opciones asociadas a los grupos, en una sola consulta
        $collectionOptions = QuestionOption::select("id", "option", "question_group_id")
            ->whereIn("question_group_id", $questionGroups->pluck("id"))
            ->get();

        // Retornar vista con los datos necesarios
        return view("admin.adminEvaluationEdit", compact("survey", "collectionOptions", "questionGroups"));
    }


   public function adminNewEvaluation()
   {
    return view("admin.adminNewEvaluation");
   }



   public function createNewEvaluation(Request $request)
   {
    $survey = new Survey;
    $survey->revision= $request->evaluationName;
    $survey->dateStart = $request->dateStart;
    $survey->dateEnd = $request->dateEnd;
    //$survey->author = $user->name;
    $survey->author = "admin1";
    $survey->status = 0;
    $survey->save();
  
    foreach($request->questions as $question)
    {
      $group=QuestionGroup::create([
        "survey_id"=>$survey->id,
      ]);
      QuestionOption::create([
        "option"=>$question["p1"],
        "question_group_id"=>$group->id,
      ]);
       QuestionOption::create([
        "option"=>$question["p2"],
        "question_group_id"=>$group->id,
      ]);
    }
    return redirect()->route("adminEvaluation");
   }

}
