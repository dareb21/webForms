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
    $coursesId = $request->query('courseId');
    $survey = new Survey;
    if($survey->where("status",1)->count() == 1)
    {
        $thisSurvey = $survey->first();    
        $questionGroups = QuestionGroup::where("survey_id", $thisSurvey->id)->get();
        $collectionOptions = QuestionOption::select("id", "option", "question_group_id")
            ->whereIn("question_group_id", $questionGroups->pluck("id"))
            ->get();    
        return view('student.studentEvaluation', compact('noClaseId','coursesId','collectionOptions','questionGroups'));
        }
          
    else
     {
            return abort(409,"Lo sentimos, algo no esta funcionando como deberia. Intente mas tarde");
      }

        
    }

    public Function studentSubmit(Request $request)
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
        "user_id"=>1,
      ]);
            foreach ($seleccionados as $option) {
               ResponseSubmit::create([
                "survey_submit_id"=>$surveySubmit->id,
                "question_option_id"=>$option,
               ]);
            }
    return response()->json(["mensaje" => "guardada"]);

    }


    public function studentThanks()
    {
        return view('student.thankyouView');
    }
}
