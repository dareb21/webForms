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
      $i=1;
      $resultados = collect();
      $thisYear= now()->year;
      $surveysOfThisYear=Survey::whereYear("created_at",$thisYear)->select("id")->get();
      
     foreach($surveysOfThisYear as $survey)
      {
       $data = DB::table("surveys as s")
    ->join("survey_submits as sb", "s.id", "=", "sb.survey_id")
    ->join("response_submits as rs", "sb.id", "=", "rs.survey_submit_id")
    ->join("question_options as qo", "rs.question_option_id", "=", "qo.id")
    ->where('s.id', $survey->id)
    ->select(
        DB::raw('SUM(qo.calification) as SumaNotaPeriodo'),
        DB::raw('count(distinct(sb.id)) as Divisor'),
    )
    ->get();
    
    $numerador=$data->pluck("SumaNotaPeriodo");
    $divisor=$data->pluck("Divisor");
   if($divisor[0] == 0)
   {
    $notaperiodo=0;
   }else
   {
    $notaperiodo=ceil($numerador[0]/$divisor[0]);
   }
   $resultados->push([
        "termScore" => $notaperiodo,
        "term" => $i,
    ]);
    $i+=1;
    }    
    $anual = ceil (($resultados->pluck("termScore"))->sum() / count($surveysOfThisYear));

    return view("admin.adminDashboard",compact("resultados","anual" ));
    }


  public function adminEvaluation()
    {
      
    $surveys = Survey::paginate(10); 
    return view("admin.adminEvaluation",compact("surveys"));
    }


    public function adminEvaluationEdit($id)
    {
        $survey = Survey::findOrFail($id);
        
        $questionGroups = QuestionGroup::where("survey_id", $survey->id)->get();

        $collectionOptions = QuestionOption::select("id", "option", "question_group_id","calification")
            ->whereIn("question_group_id", $questionGroups->pluck("id"))
            ->get();

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
       return redirect()->back()->with('alert','Llene los campos necesarios para la búsqueda.');
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


   public function adminStudentView($courseId){
   
     
    $thisYear=now()->year;
  $data = DB::table('survey_submits as sb')
    ->join('response_submits as rs', 'sb.id', '=', 'rs.survey_submit_id')
    ->join('courses as c', 'sb.course_id', '=', 'c.id')
    ->join('users as u', 'sb.user_id', '=', 'u.id') 
    ->join('users as prof', 'c.user_id', '=', 'prof.id') 
    ->join('question_options as qo', 'rs.question_option_id', '=', 'qo.id')
    ->join('surveys as s', 'sb.survey_id', '=', 's.id')
    ->where('c.id', $courseId) 
    ->whereYear('s.created_at',$thisYear)
    ->select(
        'c.name as course',
        'sb.id as submitId',
        'prof.name as professorName',
        'u.name as student',
        DB::raw('SUM(qo.calification) as scoreStudent'),
    )
    ->groupBy('prof.name', 'c.name','submitId')
    ->get();
    foreach ($data as $item) {
        $resultados[] = [            
            "score" => $item->scoreStudent,
            "profesor" => $item->professorName,
            "course" => $item->course,
            "nameStudent" => $item->student,
            "submitId"=>$item->submitId,
        ];
    } 
 
 $years = Survey::selectRAW("Year(dateStart)")
    ->distinct()
    ->get();

    return view('admin.adminStudentView',compact("years","resultados"));
  }

public function adminViewAnswer($submitId)
{
    $submit = SurveySubmit::with(['user', 'course', 'survey'])->findOrFail($submitId);
$data = DB::table('surveys as s')
    ->select(
        'qg.groupName as indicator',
        'qo.option as answer',
        'sb.observations as observation'
    )
    ->join('question_groups as qg', 's.id', '=', 'qg.survey_id')
    ->join('question_options as qo', 'qg.id', '=', 'qo.question_group_id')
    ->join('response_submits as rs', 'qo.id', '=', 'rs.question_option_id')
    ->join('survey_submits as sb', 'rs.survey_submit_id', '=', 'sb.id')
    ->join('courses as c', 'sb.course_id', '=', 'c.id')
    ->join('users as u', 'sb.user_id', '=', 'u.id')
    ->where('s.id', $submit->survey_id)
    ->where('u.id', $submit->user_id)
    ->where('c.id', $submit->course_id)
    ->distinct()
    ->orderBy('qg.groupName')
    ->get();
    foreach($data as $item)
    {
      $answer[]= [
        "indicator" =>$item->indicator,
        "answer" =>$item->answer, 
      ];
    }
     $answer[]= [
       "observation" =>$data[0]->observation, 
     ];

     
return $answer; 
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
    )
    ->groupBy('prof.name', 'c.name','c.id')
    ->first();

    if ($data && $data->totStudents > 0) {
        $score = ceil(($data->totSurvey / $data->totStudents));
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
