<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\QuestionOption;
use App\Models\QuestionGroup;
use App\Models\SurveySubmit;
use App\Models\ResponseSubmit;
use App\Models\User;
use App\Models\Survey;


class StudentController extends Controller
{
    public function studentDashboard()
    {
    
    return view("student.studentDashboard");
    }

    public function studentEvaluation(Request $request)
    {
    $thisSurvey=Survey::with(["QuestionGroup.QuestionOption"])->where("status",1)->first();
    
    if(is_null($thisSurvey))
    {
        return view("student.studentInactiveEvaluation");
    }

    $noClaseId = $request->query('noClaseId');
    $coursesId = $request->query('courseId');
    if(SurveySubmit::where("user_id",23)->where("course_id",$coursesId)->where("survey_id",$thisSurvey->id)->exists())
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
return view('student.studentEvaluation', compact('noClaseId','coursesId','data'));
    }
    public Function studentSubmit(Request $request, $courseId)
    {
       
       $survey = Survey::select("id")->where("status", 1)->first();  // Guardar en cache en caso que escale
        $surveySubmit=SurveySubmit::create([
        "DateSubmmited"=>now(),
        "survey_id"=>$survey->id,
        "course_id"=>$courseId,
        "user_id"=>27,
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
