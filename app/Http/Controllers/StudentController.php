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
    $noClaseId = $request->query('noClaseId');
    $claseId = $request->query('claseId');
    $survey = new Survey;
    if($survey->where("status",1)->count() == 1)
    {
        $thisSurvey = $survey->first();    
        $questionGroups = QuestionGroup::where("survey_id", $thisSurvey->id)->get();
        $collectionOptions = QuestionOption::select("id", "option", "question_group_id")
            ->whereIn("question_group_id", $questionGroups->pluck("id"))
            ->get();    
        }   
    else
     {
            return abort(409,"Lo sentimos, algo no esta funcionando como deberia. Intente mas tarde");
      }

        return view('student.studentEvaluation', compact('noClaseId','coursesId'));
    }

    public Function studentSubmit(Request $request)
    {
        $survey = Survey::select("id")->where("status", 1)->first();

        $surveySubmit=SurveySubmit::create([
        "DateSubmmited"=>now(),
        "survey_id"=>$survey->id,
        "user_id"=>1,
      ]);
            foreach ($request->options as $option) {
                if ($option->value == True )
                {
                    
                }
            }
    }


    public function studentThanks()
    {
        return view('student.thankyouView');
    }
}
