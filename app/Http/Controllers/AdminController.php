<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\QuestionGroup;
use App\Models\QuestionOption;
use Carbon\Carbon;
class AdminController extends Controller
{

  public function enableEvaluation($surveyId)
  {
  
    if (Survey::where("status",1)->exists())
    {
      return redirect()->back()->with('alert','Ya hay una evaluacion Activa');
    }else
    {
      Survey::where("id",$surveyId)->update([
        "status" => 1,    
      ]);
    }
    return redirect()->route("adminEvaluation");
  }



public function UnableEvaluation($surveyId)
{
    Survey::where("id", $surveyId)->where("status", 1)->update([
    "status" => 0,
    ]);
    return redirect()->route("adminEvaluation");
}



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
    $i=1;
        foreach($request->questions as $question)
    {
      $group=QuestionGroup::create([
        "survey_id"=>$survey->id,
        "groupName"=>"Grupo ". $i
      ]);
      QuestionOption::create([
        "option"=>$question["p1"],
        "question_group_id"=>$group->id,
      ]);
       QuestionOption::create([
        "option"=>$question["p2"],
        "question_group_id"=>$group->id,
      ]);
      $i+=1;
    }
    return redirect()->route("adminEvaluation");
   }

public function adminEvaluationEdited()
{
  $request = (object) session('datos');
  session()->forget('datos');

  $thisSurvey = Survey::findOrFail($surveyId);
  $dateNow = Carbon::now('etc/GMT+6');

  if ($dateNow > $thisSurvey->dateStart)
  {
   return redirect()->back()->with('alert','El periodo evaluacion ya inicio y no puede modificar.');
  }
// Logica para editar

}

public function reUseSurvey()
{
    $request = (object) session('datos');
    session()->forget('datos');
  $thisSurvey = Survey::findOrFail($request->survey_id);

  $sameName = $thisSurvey->revision == $request->revision;
  $sameDateStart=$thisSurvey->dateStart == $request->dateStart;
  $sameDateEnd=$thisSurvey->dateEnd == $request->dateEnd;
  
  if ($sameDateEnd ||$sameDateStart || $sameName)
  {
    return response()->json("no joda");
    //return redirect()->back()->with('alert','El encabezado no es permitido.');
  }
  
  $survey = new Survey;
  $survey->revision= $request->revision;
  $survey->dateStart = $request->dateStart;
  $survey->dateEnd = $request->dateEnd;
  $survey->author = "admin1";
  $survey->status = 0;
  $survey->save();

  $groups = QuestionGroup::join("question_options","question_groups.id","=","question_options.question_group_id")
  ->join("surveys","question_groups.survey_id","=","surveys.id")
  ->select("question_options.option as Options")
  ->where("surveys.id",$thisSurvey->id)
  ->get();
  $k=0;
  for ($i=1; $i<=(count($groups)/2);$i++)
{
  $questionGroup = new QuestionGroup;
  $questionOption = new QuestionOption;

  $questionGroup->survey_id = $survey->id;
  $questionGroup->groupName = "Indicador ".$i;
  $questionGroup->save();

  $questionOption->option = $groups[$k]->Options;
  $questionOption->question_group_id= $questionGroup->id;
  $questionOption->save();
  $k+=1;
  $questionOption = new QuestionOption;
   $questionOption->option = $groups[$k]->Options;
  $questionOption->question_group_id= $questionGroup->id;
  $questionOption->save();
  $k+=1;
}
return redirect()->back();
}



public function adminUpdateOrReuse(Request $request)
{
$action = $request->input('btn');
switch ($action){
  case "reuse":
        return redirect()->route("reUseSurvey")->with(['datos' => $request->all()]);
  case "update":

                break;
  default:
        return response()->json("algo salio mal");
}


}


   public function adminStudentView(){
    return view('admin.adminStudentView');
  }

  public function adminResults(){
    return view('admin.adminResults');
  }

  public function adminDelete($id){
      $survey = Survey::findOrFail($id);
      $survey->delete(); // Esto solo marca deleted_at, no borra realmente  
      return redirect()->route('adminEvaluation');
  }
}
