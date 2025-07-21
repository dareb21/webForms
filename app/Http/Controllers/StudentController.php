<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
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
    $this->cacheSurvey =  Cache::get("cacheActiveSurvey");   
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
    if(SurveySubmit::where("user_id",14)->where("section_id",$coursesId)->where("survey_id",$thisSurvey->id)->exists())
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
        $surveySubmit=SurveySubmit::create([
        "DateSubmmited"=>now(),
        "survey_id"=>$thisSurvey->id,
        "section_id"=>$courseId,
        "user_id"=>1,
        "observations"=>$request->observaciones,
      ]);
      
 $seleccionados = [];
 foreach ($request->all() as $clave => $valor) {
 if (Str::startsWith($clave,"option_"))
    {
         $optionPicked= Str::after($clave,"option_");
    }else
    {
        continue;
    }
    $seleccionados[] =[ 
        "survey_submit_id" =>$surveySubmit->id,
        "question_option_id"=>$optionPicked,
    ];
        }
ResponseSubmit::insert($seleccionados);

    return redirect()->route("studentThanks");

    }

public function studentThanks()
{
    return view("student.thankyouView");
}
 
}
