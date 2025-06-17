<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\QuestionGroup;
use App\Models\QuestionOption;

class AdminController extends Controller
{
  public function adminDashboard()
    {
        $user = (object) session('google_user');
        return view("admin.adminDashboard",compact("user"));
    }

    
  public function adminEvaluation()
    {
    $surveys = Survey::paginate(10); 
    return view("admin.adminEvaluation",compact("surveys"));
    }


    public function adminEvaluationEdit() //$surveyId
    {
      $survey=Survey::findOrFail(1);
      
      $questionGroups=QuestionGroup::where("survey_id",$survey->id)->get();

      $arrayOptions=[];

      foreach($questionGroups as $group)
      {
      $options=QuestionOption::select("id","option")->where("question_group_id",$group->id)->get();
      $arrayOptions[]= $options;
      } 
      return view("admin.adminEvaluationEdit",compact("survey","arrayOptions","questionGroups"));      
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
    $survey->available = 0;
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
