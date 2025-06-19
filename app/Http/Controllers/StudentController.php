<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;
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
    $thisSurvey = Survey::where("status", 1)->first();    
    $noClaseId = $request->query('noClaseId');
    $coursesId = $request->query('courseId');
    if(!SurveySubmit::where("user_id",1)->where("course_id",$coursesId)->where("survey_id",$thisSurvey)->exists())
    {
    $survey = new Survey;
    $questionGroups = QuestionGroup::where("survey_id", $thisSurvey->id)->get();
        $collectionOptions = QuestionOption::select("id", "option", "question_group_id")
            ->whereIn("question_group_id", $questionGroups->pluck("id"))
            ->get();    
        return view('student.studentEvaluation', compact('noClaseId','coursesId','collectionOptions','questionGroups'));
        }
        else
            {
                return view("student.thankyouView");
        }

    }

    public Function studentSubmit(Request $request, $courseId)
    {
         $seleccionados = [];

            foreach ($request->all() as $clave => $valor) {
             if (str_starts_with($clave, 'option_') && $valor === 'on') {
             preg_match('/option_(\d+)/', $clave, $match);
             if (isset($match[1])) {
            $seleccionados[] = (int) $match[1];
        }
    }
}   
        $survey = Survey::select("id")->where("status", 1)->first();

        $surveySubmit=SurveySubmit::create([
        "DateSubmmited"=>now(),
        "survey_id"=>$survey->id,
        "course_id"=>$courseId,
        "user_id"=>1,
        "observations"=>$request->observaciones,

      ]);
            foreach ($seleccionados as $option) {
               ResponseSubmit::create([
                "survey_submit_id"=>$surveySubmit->id,
                "question_option_id"=>$option,
               ]);
            }
    return redirect()->route("studentThanks");

    }

public function studentThanks()
{
    return view("student.thankyouView");
}
  
}
