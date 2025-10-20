<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Models\QuestionOption;
use App\Models\QuestionGroup;
use App\Models\SurveySubmit;
use App\Models\ResponseSubmit;
use App\Models\User;
use App\Models\Survey;


class StudentController extends Controller
{
    private $cacheSurvey;

    public function __construct()
    {
    $this->cacheSurvey =  Cache::get("ActiveSurvey");   
    }

    public function studentDashboard()
    {

    return view("student.studentDashboard");
    }

    public function studentEvaluation(Request $request)
    {
    
    $thisSurvey = $this->cacheSurvey; 
    if(!$thisSurvey)
    {
        return view("student.studentInactiveEvaluation");
    }

    $courseArrayPosition = $request->query('courseArrayPosition');
    $coursesId = $request->query('courseId');
    if(SurveySubmit::where("user_id",Auth::user()->id)->where("section_id",$coursesId)->where("survey_id",$thisSurvey->id)->exists())
    {
              return view("student.thankyouView");
     }
        
  $questionGroups=$thisSurvey->QuestionGroup;
    $data=[];
        foreach ($questionGroups as $questionGroup)
        {
        $data[]=[
        "groupName" => $questionGroup->groupName,
        "option1Id" => $questionGroup->QuestionOption[0]->id,
        "option1" => $questionGroup->QuestionOption[0]->option,
        "option2Id" => $questionGroup->QuestionOption[1]->id,
        "option2" => $questionGroup->QuestionOption[1]->option,
        ];
}   

$data = collect($data)->groupBy('groupName');
return view('student.studentEvaluation', compact('courseArrayPosition','coursesId','data'));
    }

public Function studentSubmit(Request $request, $courseId)
    {
 $thisSurvey = $this->cacheSurvey;    
 $replies = [];
$repliesSended = $request->only(array_filter(array_keys($request->all()), fn($key) => str_starts_with($key, 'option')));
    if (count($repliesSended) != count($thisSurvey->QuestionGroup) )
    {
        return redirect()->back()->with('alert','Favor llenar todos los campos disponibles');
    }

foreach ($repliesSended as $clave => $valor) {
        $replies[] =[ 
        "question_option_id"=>Str::after($clave,"option_"),
        ];
    }

 $surveySubmit=SurveySubmit::create([
        "DateSubmmited"=>now(),
        "survey_id"=>$thisSurvey->id,
        "section_id"=>$courseId,
        "user_id"=>Auth::user()->id,
        "observations"=>$request->observaciones,
      ]);

foreach ($replies as &$reply) {
    $reply["survey_submit_id"] = $surveySubmit->id;
   }
    unset($reply);
    ResponseSubmit::insert($replies);
    return redirect()->route("studentThanks");

    }

public function studentThanks()
{
    return view("student.thankyouView");
}
 
}
