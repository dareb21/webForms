<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\QuestionOption;
use App\Models\QuestionGroup;
use App\Models\SurveySubmit;
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

        if ($survey->where("status", 1)->count() == 1) {
            $thisSurvey = $survey->where("status", 1)->first();    

            $questionGroups = QuestionGroup::where("survey_id", $thisSurvey->id)->get();

            $collectionOptions = QuestionOption::select("id", "option", "question_group_id")
                ->whereIn("question_group_id", $questionGroups->pluck("id"))
                ->get();    

            return view('student.studentEvaluation', compact('questionGroups', 'collectionOptions','noClaseId','coursesId'));
        } else {
            return response()->json("Algo mal ha ocurrido");
        }

        return view('student.studentEvaluation', compact('noClaseId','coursesId'));
    }

    public function studentThanks()
    {
        return view('student.thankyouView');
    }
}
