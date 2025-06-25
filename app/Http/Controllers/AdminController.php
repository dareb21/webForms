<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\QuestionGroup;
use App\Models\QuestionOption;
use App\Models\SurveySubmit;
use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
    Survey::where("id", $surveyId)->update([
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
        $collectionOptions = QuestionOption::select("id", "option", "question_group_id","calification")
            ->whereIn("question_group_id", $questionGroups->pluck("id"))
            ->get();

        // Retornar vista con los datos necesarios
        return view("admin.adminEvaluationEdit", compact("survey", "collectionOptions", "questionGroups"));
    }


   public function adminNewEvaluation()
   {
    return view("admin.adminNewEvaluation");
   }

   public function evaluationSearch(Request $request)
   {
   if (!$request->filled('adminSearch') || !$request->filled('adminSearchSelect')) 
   {
       return response()->json("favor llenar todos los campos");
    }

    switch ($request->adminSearchSelect)
    {
        case "autor":
          $searchParameter="Author";
          $type = "text";
          break;

        case "revision":
            $searchParameter="revision";
            $type = "text";
          break;

        case "fechaInicio":
            $searchParameter="dateStart";
            $type="date";
          break;

      default:
          abort(401);
    }
    if ($type == "text")
    {
         $surveys = Survey::where($searchParameter,"LIKE","%".$request->adminSearch."%")->paginate(10);  
    }else
    {
        $date = Carbon::createFromFormat('d-m-Y', $request->adminSearch)->format('Y-m-d');
        $surveys = Survey::whereDate($searchParameter,$date)->paginate(10);  
    }
       return view("admin.adminEvaluation",compact("surveys"));

   }

   public function resultSearch(Request $request)
   {
      dd($request->all());
   }




   public function createNewEvaluation(Request $request)
   {
    //Validar que la suma de todo no pase de 20
    $survey = new Survey;
    $survey->revision= $request->evaluationName;
    $survey->dateStart = $request->dateStart;
    $survey->dateEnd = $request->dateEnd;
    $survey->term = $request->term;
    $survey->author = "admin1";
    $survey->status = 0;
    $survey->save();
    $i=1;
    $k=1;
        foreach($request->questions as $question)
    {
      $group=QuestionGroup::create([
        "survey_id"=>$survey->id,
        "groupName"=>"Indicador ". $i
      ]);
      QuestionOption::create([
        "option"=>$question["p1"],
        "calification"=>$request->cal[$k]["c1"],
        "question_group_id"=>$group->id,
      ]);
       QuestionOption::create([
        "option"=>$question["p2"],
        "calification"=>$request->cal[$k]["c2"],
        "question_group_id"=>$group->id,
      ]);
      $i+=1;
      $k+=1;
    }
    return redirect()->route("adminEvaluation");
   }


public function adminEvaluationEdited()
{
 
  $request = (object) session('datos');
  //session()->forget('datos');
  $thisSurvey = Survey::findOrFail($request->surveyId);
  $dateNow = Carbon::now('etc/GMT+6');
  if ($dateNow > $thisSurvey->dateStart)
  {
   return redirect()->back()->with('alert','El periodo evaluacion ya inicio y no puede modificar.');
  }

if (isset($request->grupos))
{
$numGroup = QuestionGroup::where("survey_id",$request->surveyId)->count();
$i=$numGroup+1;
foreach ( $request->grupos as $index => $grupo)
  {
 $group=QuestionGroup::create([
        "survey_id"=>$thisSurvey->id,
        "groupName"=>"Indicador ". $i
      ]);
      
  QuestionOption::create([
    "option"=>$grupo["pregunta1"],
        "calification"=>$request->cal[$index]["c1"],
        "question_group_id"=>$group->id,
      ]);

    QuestionOption::create([
    "option"=>$grupo["pregunta2"],
        "calification"=>$request->cal[$index]["c2"],
        "question_group_id"=>$group->id,
      ]);

      $i+=1;
  }
}else
{
}


return redirect()->route('adminEvaluation');
}

public function reUseSurvey()
{
    $request = (object) session('datos');
    session()->forget('datos');
  $thisSurvey = Survey::findOrFail($request->survey_id);

  $sameName = $thisSurvey->revision == $request->evaluationName;
  $sameDateStart=$thisSurvey->dateStart == $request->dateStart;
  $sameDateEnd=$thisSurvey->dateEnd == $request->dateEnd;
  
  if ($sameDateEnd ||$sameDateStart || $sameName)
  {
    return response()->json("no joda");
    //return redirect()->back()->with('alert','El encabezado no es permitido.');
  }
  
  $survey = new Survey;
  $survey->revision= $request->evaluationName;
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
        return redirect()->route("adminEvaluationEdited")->with(['datos' => $request->all()]);
                break;
  default:
        return response()->json("algo salio mal");
}


}


   public function adminStudentView(){
    
    return view('admin.adminStudentView');
  }

  public function adminResults(){
$thisYear=now()->year;
    
$courses = Course::paginate(3);

foreach ($courses as $course)
{
  $data = DB::table('survey_submits as sb')
    ->join('response_submits as rs', 'sb.id', '=', 'rs.survey_submit_id')
    ->join('courses as c', 'sb.course_id', '=', 'c.id')
    ->join('users as u', 'sb.user_id', '=', 'u.id') 
    ->join('users as prof', 'c.user_id', '=', 'prof.id') 
    ->join('question_options as qo', 'rs.question_option_id', '=', 'qo.id')
    ->join('surveys as s', 'sb.survey_id', '=', 's.id')
    ->where('c.id', $course->id) 
    ->whereYear('s.created_at',$thisYear)
    ->select(
        'c.name as course',
        'prof.name as professorName',
         'c.id as courseId',
        DB::raw('SUM(qo.calification) as totSurvey'),
        DB::raw("COUNT(DISTINCT sb.id) AS totStudents"),
        DB::raw("COUNT(DISTINCT sb.survey_id) AS surveyCount"),
    )
    ->groupBy('prof.name', 'c.name','c.id')
    ->first();

    if ($data && $data->surveyCount > 0 && $data->totStudents > 0) {
        $score = ceil(($data->totSurvey / $data->totStudents) / $data->surveyCount);
        $resultados[] = [
            "score" => $score,
            "profesor" => $data->professorName,
            "course" => $data->course,
            "courseId" =>$data->courseId
        ];
    } 
    
}
 $years = Survey::selectRAW("Year(dateStart)")
    ->distinct()
    ->get();

    return view('admin.adminResults',compact("years","resultados"));
  }

  public function adminDelete($id){
      $survey = Survey::findOrFail($id);
      $survey->delete(); // Esto solo marca deleted_at, no borra realmente  
      return redirect()->route('adminEvaluation');
  }


}
