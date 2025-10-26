<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\QuestionGroup;
use App\Models\QuestionOption;
use App\Models\SurveySubmit;
use App\Models\Course;
use App\Models\School;
use App\Models\Section;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\adminResultsExcel;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;
use App\Services\AcademicServices;
class AdminController extends Controller
{

  private $adminService;
  public function __construct(AcademicServices $adminService)
  {
      $this->adminService = $adminService;
  }

  public function enableEvaluation($surveyId)
  {
  
    if (Survey::where("status",1)->exists())
    {
      return redirect()->back()->with('alert','Ya hay una evaluacion Activa');
    }
    $dateNow = Carbon::now('etc/GMT+6');
    $thisSurvey = Survey::find($surveyId);

    if ($dateNow>=$thisSurvey->dateEnd)
    {
            return redirect()->back()->with('alert','La evaluacion llego a su fecha de cierre.');
    }

    $thisSurvey->update([
      "status" =>1,
    ]);
    return redirect()->back();
  }



public function UnableEvaluation($surveyId)
{
  $thisSurvey= Survey::select("status","id")->where("id", $surveyId)->first();
if ($thisSurvey->status === 1)
  {
   $thisSurvey->status = 0;
    $thisSurvey->save();
  Survey::ForgetCache();
  }
   
  return redirect()->back();
}
  public function adminDashboard(Request $request)
    {
    $thisSchool = 0;
    if ($request->schoolSegmentation > 0)
    {
        $thisSchool = $request->schoolSegmentation;
    }
    $dropDown =$this->adminService->dropDown();
    $schoolInfo =$this->adminService->sections($thisSchool); 
    $dashboard = $this->adminService->dashboard($thisSchool);
    $lowerAndHigher = $this->adminService->lowerAndHigher($thisSchool);
     return view("admin.adminDashboard",compact("dashboard","dropDown","schoolInfo","lowerAndHigher"));
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
  try {  
$request->validate([
    "evaluationName" => "required|string",
    "term" => "required|integer|in:1,2,3",
    "dateStart" => "required|date|after_or_equal:today",
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

  } catch (\Illuminate\Validation\ValidationException $e) {
    return response()->json([
        'errors' => $e->validator->errors(),
    ], 422);
  }
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


   public function adminStudentView($sectionId)
   {
  $adminStudentView = $this->adminService->studentView($sectionId);
  $years = Survey::selectRAW("Year(dateStart)")
      ->distinct()
      ->get(); 

  return view('admin.adminStudentView',compact("years","adminStudentView"));
  }

public function adminViewAnswer($submitId)
{
    return $this->adminService->viewAnswer($submitId);
     
}

public function adminResults(){

  $adminResults =  $this->adminService->results();
  dd($adminResults);
  if ($adminResults === false)
  {
    $noInfo=true;
    return view('admin.adminResults',compact("noInfo"));
  }
  $years = Survey::selectRaw("YEAR(dateStart) as year")
    ->distinct()
    ->orderBy('year', 'desc')
    ->get();


  return view('admin.adminResults',compact("adminResults","years"));}




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
$sections = Section::has('submits')->withCount('submits')->paginate(10);

$hasData = False;
foreach ($sections as $section)
{
  $data = DB::table('survey_submits as sb')
    ->join('response_submits as rs', 'sb.id', '=', 'rs.survey_submit_id')
    ->join('sections as sec', 'sb.section_id', '=', 'sec.id')
    ->join('users as u', 'sb.user_id', '=', 'u.id') 
    ->join('users as prof', 'sec.user_id', '=', 'prof.id') 
    ->join('question_options as qo', 'rs.question_option_id', '=', 'qo.id')
    ->join('surveys as s', 'sb.survey_id', '=', 's.id')
    ->where('sec.id', $section->id) 
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

public function adminControlCourses()
{
  $courses = [];
  $data = Section::with(["professor","course"])->paginate(10);
  if(!$data)
  {
    $noInfo = True;
    return view("admin.adminControlCourses",compact("noInfo"));
  }
  foreach ($data as $item)
  {
    $courses[]=[
      "courseName" => $item->course->name,
      "courseProfessor"=>$item->professor->name,
      "sectionId" =>$item->id,
      "sectionCode"=>$item->code,
      "courseStatus"=>$item->status,
    ];
  }
  $changePagination = true;
  return view("admin.adminControlCourses",compact("courses","data","changePagination"));
}


public function blockCourse($sectionId)
{
$thisCourse = Section::findOrFail($sectionId);
$thisCourse->update(["status" => 0]);
return back()->with('success', 'Curso bloqueado correctamente');

}

public function unblockCourse($sectionId)
{
   $thisCourse = Section::findOrFail($sectionId);
$thisCourse->update(["status" => 1]);
return back()->with('success', 'Curso activado correctamente');

}

public function searchCourse(Request $request)
{
$userSearch = User::where("name", "LIKE","%". $request->courseSearch. "%")->where("role","Catedrático")->select("id","name")->first();
if (!$userSearch)
{
  $noInfo = True;
  $changePagination = false;
  return view("admin.adminControlCourses",compact("noInfo","changePagination"));
}
$data = User::with(["section.Course"])->findOrFail($userSearch->id);
if(!$data->section)
  {
    $changePagination = false;
    $noInfo = True;
    return view("admin.adminControlCourses",compact("noInfo","changePagination"));
  }
  foreach ($data->section as $item)
  {
    $courses[]=[
      "courseName" => $item->course->name,
      "courseProfessor"=>$data->name,
      "sectionId" =>$item->id,
      "courseStatus"=>$item->status,
      "sectionCode"=>$item->code,
    ];
  }
  
  $changePagination = false;
  return view("admin.adminControlCourses",compact("courses","changePagination"));
}

public function exportarResultadosPDF()
{
  $years = Survey::selectRAW("Year(dateStart)")
    ->distinct()
    ->get();
$courses = Section::has('submits')->paginate(10);
if($courses -> isEmpty()){
  $noInfo=True;
  return view('admin.adminResults',compact("noInfo"));
}
$coursesId=$courses->pluck("id")->toArray();
$thisYear = session()->pull('year', now()->year);
    
    $data = DB::table('survey_submits as sb')
        ->join('response_submits as rs', 'sb.id', '=', 'rs.survey_submit_id')
            ->join('sections as sec', 'sb.section_id', '=', 'sec.id')
            ->join('courses as c', 'sec.course_id', '=', 'c.id')
            ->join('users as u', 'sb.user_id', '=', 'u.id')
            ->join('users as prof', 'sec.user_id', '=', 'prof.id')
            ->join('question_options as qo', 'rs.question_option_id', '=', 'qo.id')
            ->join('surveys as s', 'sb.survey_id', '=', 's.id')
        ->whereIn('sec.id', $coursesId) 
        ->whereYear('s.created_at',$thisYear)
        ->select(
               'prof.name as professorName',
                'prof.id as professorId',
                'c.name as courses',
                'sec.id as sectionId',
             
            DB::raw('SUM(qo.calification) as totSurvey'),
            DB::raw("COUNT(DISTINCT sb.id) AS totStudents"),
            )
        ->groupBy('prof.name', 'sec.id')
    ->paginate(10);
    $dataResults = [];
    $dataId = $data->pluck("professorId")->unique();
     foreach ($dataId as $index => $id) {
            $thisItem = $data->where("professorId", $id);
            $totsurvey = ($thisItem->pluck("totSurvey"))->sum();
            $divisor = ($thisItem->pluck("totStudents"))->sum();
            $avgScore = round($totsurvey / $divisor);

            $coursesData = $thisItem->map(function ($i) {
                $totSurveyPerCourse = $i->totSurvey;
                $totStudentPerCourse = $i->totStudents;
                $totPerCourse = round($totSurveyPerCourse / $totStudentPerCourse);
                return [
                    "sectionId" => $i->sectionId,
                   
                    "course" => $i->courses,
                    "totPerCourse" => $totPerCourse
                ];
            });
            $coursesDataArray = $coursesData->toArray();
            $dataResults[] = [
                "professorName" => $data[$index]->professorName,
                "professorScoreAvg" => $avgScore,
                "coursesData" => $coursesDataArray,
            ];
        }
  return Pdf::loadView('pdf.adminResultsPDF', compact('dataResults'))
              ->setPaper('a4', 'portrait')
              ->download('admin-resultados.pdf');
}

public function adminResultsExcel()
{
  $courses = Section::has('submits')->paginate(10);
  $thisYear = session()->pull('year', now()->year);
    foreach ($courses as $course)
     {
        $data = DB::table('survey_submits as sb')
        ->join('response_submits as rs', 'sb.id', '=', 'rs.survey_submit_id')
        ->join('sections as sec', 'sb.section_id', '=', 'sec.id')
        ->join('users as u', 'sb.user_id', '=', 'u.id') 
        ->join('users as prof', 'sec.user_id', '=', 'prof.id') 
        ->join('question_options as qo', 'rs.question_option_id', '=', 'qo.id')
        ->join('surveys as s', 'sb.survey_id', '=', 's.id')
        ->where('sec.id', $course->id) 
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
  return Excel::download(new adminResultsExcel($resultados), 'reporteAdmin-resultados.xlsx');
}

}
