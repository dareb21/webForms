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
use Illuminate\Support\Facades\Validator;
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


   public function createNewEvaluation(Request $request)
   {
    
    $request->validate([
    "evaluationName" => "required|string",
    "term" => "required|integer|in:1,2,3",
    "dateStart" =>"required|date|after:today",
    "dateEnd" => "required|date|after:dateStart",
    "cal" => "required|array",
    "cal.*.c1" => "required|numeric|min:1",
    "cal.*.c2" => "required|numeric|min:1",
    'questions' => 'required|array',
    'questions.*.p1' => 'required|string|min:5',
    'questions.*.p2' => 'required|string|min:5',
]);

  $year = Carbon::parse($request->dateStart)->year;
  $thisYears = Survey::whereYear('dateStart', $year)->count();  
    if ($thisYears==3)
    {
        return response()->json("Accion no permitida, para el año solicitado ya hay 3 encuestas. Si desea continuar, elimine una o modifique la fecha.");
    }
$terms =Survey::where('term', $request->term)->whereYear('dateStart', $year)->exists();

if ($terms) {
return response()->json("Accion no permitida, para el periodo solicitado ya hay una encuesta asignada. Si desea continuar, elimine o modifique la encuesta.");
}
 $cal = $request->input('cal');
$sumtot=0;
   foreach ($cal as $key => &$val) {
    $suma= $val['c1'] + $val['c2'];
    $sumtot+=$suma;
   }
if ($sumtot!=20)
{
  return response()->json("La calificacion supera  es menor a 20 puntos, modifique los valores porfavor.");
}
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
        "groupName"=>$i
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
  session()->forget('datos');
    
$cal = $request->cal;
$sumtot=0;
   foreach ($cal as $key => &$val) {
    $suma= $val['c1'] + $val['c2'];
    $sumtot+=$suma;
   }

if ($sumtot!=20)
{
  return response()->json("La calificacion es mayor o menor que 20 puntos, valide los valores porfavor.");
}
  $thisSurvey = Survey::findOrFail($request->surveyId);
  $dateNow = Carbon::now('etc/GMT+6');
  if ($dateNow >= $thisSurvey->dateStart)
  {
   return redirect()->back()->with('alert','El periodo evaluacion ya inicio y no puede se puede modificar.');
  }

if (isset($request->grupos))
{
$numGroup = QuestionGroup::where("survey_id",$request->surveyId)->count();
$i=$numGroup+1;
foreach ( $request->grupos as $index => $grupo)
  {
 $group=QuestionGroup::create([
        "survey_id"=>$thisSurvey->id,
        "groupName"=> $i
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
  $groups = QuestionGroup::join("surveys", "question_groups.survey_id", "=", "surveys.id")
    ->join("question_options", "question_groups.id", "=", "question_options.question_group_id")
    ->where("surveys.id", $thisSurvey->id)
    ->select(
        "question_groups.id as groupId",
        "question_options.id as optionId"
    )
    ->distinct()
    ->get();

   $groupsId = array_unique($groups->pluck("groupId")->toArray());
  $questionId= $groups->pluck("optionId");

  Survey::where('id', $thisSurvey->id)
      ->update(['revision' => $request->evaluationName,
      'dateEnd' => $request->dateEnd,
      'term' => $request->term,
      'dateStart'=> $request->dateStart]);

  $firstArrayKey=array_key_first($request->options);

  $subFirstArrayKey=array_key_first($request->options[$firstArrayKey]);
$i=0;
foreach ($groupsId as $groupId) {
    QuestionOption::where("question_group_id", $groupId)
        ->where("id", $questionId[$i])
        ->update([
            "option" => $request->options[$firstArrayKey][$subFirstArrayKey],
            "calification" => $request->cal[$firstArrayKey]["c1"]
        ]);

    $subFirstArrayKey += 1;
      $i+=1;
    QuestionOption::where("question_group_id", $groupId)
        ->where("id", $questionId[$i])
        ->update([
            "option" => $request->options[$firstArrayKey][$subFirstArrayKey],
            "calification" => $request->cal[$firstArrayKey]["c2"]
        ]);

    $firstArrayKey += 1;
    $subFirstArrayKey += 1;
    $i+=1;
}


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
  $survey->term = $request->term;
  $survey->status = 0;
  $survey->save();

  $groups = QuestionGroup::join("question_options","question_groups.id","=","question_options.question_group_id")
  ->join("surveys","question_groups.survey_id","=","surveys.id")
  ->select("question_options.option as Options")
  ->where("surveys.id",$thisSurvey->id)
  ->get();
  $k=0;
  $firstArrayKey=array_key_first($request->cal);
  
  for ($i=1; $i<=(count($groups)/2);$i++)
{
  $questionGroup = new QuestionGroup;
  $questionOption = new QuestionOption;

  $questionGroup->survey_id = $survey->id;
  $questionGroup->groupName = $i;
  $questionGroup->save();

  $questionOption->option = $groups[$k]->Options;
  $questionOption->question_group_id= $questionGroup->id;
  $questionOption->calification =$request->cal[$firstArrayKey]["c1"];
  $questionOption->save();
  $k+=1;
  $questionOption = new QuestionOption;
   $questionOption->option = $groups[$k]->Options;
  $questionOption->question_group_id= $questionGroup->id;
  $questionOption->calification =$request->cal[$firstArrayKey]["c2"];
  $questionOption->save();
  $k+=1;
  $firstArrayKey+=1;
}
return redirect()->route("adminEvaluation");
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


   public function adminStudentView($courseId, Request $request){
  

      if (is_null($request->annualPeriod))
      {
      $data = DB::table('survey_submits as sb')
      ->join('response_submits as rs', 'sb.id', '=', 'rs.survey_submit_id')
      ->join('courses as c', 'sb.course_id', '=', 'c.id')
      ->join('users as u', 'sb.user_id', '=', 'u.id') 
      ->join('users as prof', 'c.user_id', '=', 'prof.id') 
      ->join('question_options as qo', 'rs.question_option_id', '=', 'qo.id')
      ->join('surveys as s', 'sb.survey_id', '=', 's.id')
      ->where('c.id', $courseId)
      ->whereYear('s.created_at',now()->year)
      ->select(
          'c.name as course',
          'sb.id as submitId',
          'prof.name as professorName',
          'u.name as student',
          DB::raw('SUM(qo.calification) as scoreStudent'),
      )
      ->groupBy('prof.name', 'c.name','submitId')
      ->paginate(10);
      }
      else
      {

$data = DB::table('survey_submits as sb')
      ->join('response_submits as rs', 'sb.id', '=', 'rs.survey_submit_id')
      ->join('courses as c', 'sb.course_id', '=', 'c.id')
      ->join('users as u', 'sb.user_id', '=', 'u.id') 
      ->join('users as prof', 'c.user_id', '=', 'prof.id') 
      ->join('question_options as qo', 'rs.question_option_id', '=', 'qo.id')
      ->join('surveys as s', 'sb.survey_id', '=', 's.id')
      ->where('c.id', $courseId)
      ->where('s.term', $request->annualPeriod) 
      ->whereYear('s.created_at',$request->annualYear)
      ->select(
          'c.name as course',
          'sb.id as submitId',
          'prof.name as professorName',
          'u.name as student',
          DB::raw('SUM(qo.calification) as scoreStudent'),
      )
      ->groupBy('prof.name', 'c.name','submitId')
      ->paginate(10);
      }

      if ($data)
      {
      foreach ($data as $item) {
          $resultados[] = [            
              "score" => $item->scoreStudent,
              "profesor" => $item->professorName,
              "course" => $item->course,
              "nameStudent" => $item->student,
              "submitId"=>$item->submitId,
          ];
          }    
        }else{
          $resultados[] = [
            "score" => 0,
            "profesor" => "n/a",
            "course" => $course->name,
            "courseId" =>0
        ];
      }
  
  $years = Survey::selectRAW("Year(dateStart)")
      ->distinct()
      ->get();

      return view('admin.adminStudentView',compact("years","resultados","data"));
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

  $thisYear = session()->pull('year', now()->year);
  $courses = Course::paginate(10);
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
        $score = round(($data->totSurvey / $data->totStudents));
        $resultados[] = [
            "score" => $score,
            "profesor" => $data->professorName,
            "course" => $data->course,
            "courseId" =>$data->courseId
        ];
    }
    else{
       $resultados[] = [
            "score" => 0,
            "profesor" => "n/a",
            "course" => $course->name,
            "courseId" =>0
        ];
}
}
 $years = Survey::selectRAW("Year(dateStart)")
    ->distinct()
    ->get();

  return view('admin.adminResults',compact("years","resultados","courses"));
  }



public function resultSearch(Request $request)
   {
     $thisYear=$request->annualYear;
     $term=$request->annualPeriod;
     if ($term==4)
     {
       return redirect()->route("adminResults")->with(['year' => $thisYear]);;
     }
$courses = Course::paginate(10);

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
    ->where('s.term',$term)
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
        $score = round(($data->totSurvey / $data->totStudents));
        $resultados[] = [
            "score" => $score,
            "profesor" => $data->professorName,
            "course" => $data->course,
            "courseId" =>$data->courseId
        ];
    }else{
       $resultados[] = [
            "score" => 0,
            "profesor" => "n/a",
            "course" => $course->name,
            "courseId" =>0
        ];
    }

}
 $years = Survey::selectRAW("Year(dateStart)")
    ->distinct()

    ->get();
    // TE QUEDASTE HACIENDO EL FILTRO PARA RESULTADOS
    return view('admin.adminResults',compact("years","resultados","courses"));

   }







  public function adminDelete($id){
      $survey = Survey::findOrFail($id);
      $survey->delete(); // Esto solo marca deleted_at, no borra realmente  
      return redirect()->route('adminEvaluation');
  }

  
public function studentSearch()
{
  dd($request);
}

}
