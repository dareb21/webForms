<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\QuestionGroup;
use App\Models\QuestionOption;
use App\Models\SurveySubmit;
use App\Models\Course;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{

  public function enableEvaluation($surveyId)
  {
  
    if (Survey::where("status",1)->exists())
    {
      return redirect()->back()->with('alert','Ya hay una evaluacion Activa');
    }
     $thisSurvey = 
    $dateNow = Carbon::now('etc/GMT+6');
    $thisSurvey = Survey::find($surveyId);

    if ($dateNow>=$thisSurvey->dateEnd)
    {
            return redirect()->back()->with('alert','La evaluacion llego a su fecha de cierre.');
    }

    $thisSurvey->update([
      "status" =>1,
    ]);
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
     // $thisIds =  $surveysOfThisYear->pluck("id");
    
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
    $anual = round(($resultados->pluck("termScore"))->sum() / count($surveysOfThisYear));


  $allProfessor = User::where("role","professor")->count();
  $amountProfessors =Course::has('submits')->get();
  $professorsEvaluated=$amountProfessors->pluck("user_id")->unique()->count();


        return view("admin.adminDashboard",compact("resultados","anual","allProfessor","professorsEvaluated" ));
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
    "dateStart" => "required|date|after:today",
    "dateEnd" => "required|date|after:dateStart",
    "cal" => "required|array",
    "cal.*.c1" => "required|numeric|min:0",
    "cal.*.c2" => "required|numeric|min:0",
    'questions' => 'required|array',
    'questions.*.p1' => 'required|string|min:5',
    'questions.*.p2' => 'required|string|min:5',
],[
    "evaluationName.required" => "El nombre de la revisión está vacío, por favor llénelo.",
    "term.required" => "El período es obligatorio.",
    "term.in" => "El período debe ser 1, 2 o 3.",
    "dateStart.required" => "La fecha de apertura es obligatoria.",
    "dateStart.after" => "La fecha de apertura debe ser posterior a hoy.",
    "dateEnd.required" => "La fecha de cierre es obligatoria.",
    "dateEnd.after" => "La fecha de cierre debe ser después de la fecha de apertura.",
    
    "cal.required" => "Debe ingresar al menos una calificación.",
    "cal.*.c1.required" => "La calificación C1 es obligatoria.",
    "cal.*.c1.numeric" => "La calificación C1 debe ser un número.",
    "cal.*.c1.min" => "La calificación C1 no puede ser negativa.",
    "cal.*.c2.required" => "La calificación C2 es obligatoria.",
    "cal.*.c2.numeric" => "La calificación C2 debe ser un número.",
    "cal.*.c2.min" => "La calificación C2 no puede ser negativa.",

    "questions.required" => "Debe ingresar al menos una pregunta.",
    "questions.*.p1.required" => "La pregunta P1 es obligatoria.",
    "questions.*.p1.string" => "La pregunta P1 debe ser texto.",
    "questions.*.p1.min" => "La pregunta P1 debe tener al menos 5 caracteres.",
    "questions.*.p2.required" => "La pregunta P2 es obligatoria.",
    "questions.*.p2.string" => "La pregunta P2 debe ser texto.",
    "questions.*.p2.min" => "La pregunta P2 debe tener al menos 5 caracteres.",
]);


  $year = Carbon::parse($request->dateStart)->year;
  $thisYears = Survey::whereYear('dateStart', $year)->count();  
    if ($thisYears==3)
    {
        return redirect()->back()->with('alert','No se pueden mas de 3 encuestas al año.');
    }
$terms =Survey::where('term', $request->term)->whereYear('dateStart', $year)->exists();

if ($terms) {
    return redirect()->back()->with('alert','Ya hay una evaluacion creada en este período, favor elegir otro.');
}
 $cal = $request->input('cal');
$sumtot=0;
   foreach ($cal as $key => &$val) {
    $suma= $val['c1'] + $val['c2'];
    $sumtot+=$suma;
   }
if ($sumtot!=20)
{
  return redirect()->back()->with('alert','La calificacion no cuadra con los 20 puntos, favor revise.')->withInput();
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
  return redirect()->back()->with('alert','La calificacion no cuadra con los 20 puntos, favor revise.')->withInput();
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

    return redirect()->back()->with('alert','El encabezado no es permitido.');
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
$years = Survey::selectRAW("Year(dateStart)")
    ->distinct()
    ->get();
$courses = Course::has('submits')->paginate(10);
if($courses -> isEmpty()){
  $noInfo=True;
  return view('admin.adminResults',compact("noInfo"));
}
$thisYear = session()->pull('year', now()->year);
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
            'prof.name as professorName',
            DB::raw('SUM(qo.calification) as totSurvey'),
            DB::raw("COUNT(DISTINCT sb.id) AS totStudents"),
            )
        ->groupBy('prof.name')
        ->first();
      if (!$data)
    {
      $noInfo=True;
        return view('admin.adminResults',compact("years","noInfo"));
    }  
      $score = round(($data->totSurvey / $data->totStudents));
      $resultados[] = [
            "score" => $score,
            "profesor" => $data->professorName,
            "course" => $course->name,
            "courseId" =>$course->id
        ];

}
  return view('admin.adminResults',compact("years","resultados","courses"));
}



public function resultSearch(Request $request)
   {
     $years = Survey::selectRAW("Year(dateStart)")
    ->distinct()
    ->get();
  
     $thisYear=$request->annualYear;
     $term=$request->annualPeriod;
     if ($term==4)
     {
       return redirect()->route("adminResults")->with(['year' => $thisYear]);;
     }
$courses = Course::has('submits')->withCount('submits')->paginate(10);

$hasData = False;
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
        'prof.name as professorName',
        DB::raw('SUM(qo.calification) as totSurvey'),
        DB::raw("COUNT(DISTINCT sb.id) AS totStudents"),
    )
    ->groupBy('prof.name')
    ->first();

if (!$data)
    {
        continue;
    }  
$hasData=True;
$score = round(($data->totSurvey / $data->totStudents));
$resultados[] = [
           "score" => $score,
            "profesor" => $data->professorName,
            "course" => $course->name,
            "courseId" =>$course->id
 ];
}
if (!$hasData)  
 {
  return redirect()->back()->with('alert','No hay info en ese período.');
}


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
public function adminControlCourses()
{
  $courses = [];
  $data = Course::with(["professor"])->paginate(10);
  foreach ($data as $item)
  {
    $courses[]=[
      "courseName" => $item->name,
      "courseProfessor"=>$item->professor->name,
      "courseId" =>$item->id,
      "courseStatus"=>$item->status,
    ];
  }
  return view("admin.adminControlCourses",compact("courses","data"));
}


public function blockCourse($courseId)
{
    $thisCourse = Course::find($courseId);
    $thisCourse->update([
      "status"=>0,
    ]);
    return redirect()->route("adminControlCourses");
}

public function unblockCourse($courseId)
{
    $thisCourse = Course::find($courseId);
    $thisCourse->update([
      "status"=>1,
    ]);
    return redirect()->route("adminControlCourses");
}



public function exportarResultadosPDF()
{
  $courses = Course::has('submits')->paginate(10);
  $thisYear = session()->pull('year', now()->year);
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
            'prof.name as professorName',
            DB::raw('SUM(qo.calification) as totSurvey'),
            DB::raw("COUNT(DISTINCT sb.id) AS totStudents"),
            )
        ->groupBy('prof.name')
        ->first();
        $score = round(($data->totSurvey / $data->totStudents));
        $resultados[] = [
            "score" => $score,
            "profesor" => $data->professorName,
            "course" => $course->name,
            "courseId" =>$course->id
        ];
      }
  return Pdf::loadView('pdf.adminResultsPDF', compact('resultados'))
              ->setPaper('a4', 'portrait')
              ->download('resultados.pdf');
}
}
