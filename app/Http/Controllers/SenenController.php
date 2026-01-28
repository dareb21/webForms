<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Survey;
use Illuminate\Support\Facades\DB;
use App\Models\Course;
use App\Models\Section;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\deanSchoolExcel;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use App\Models\SurveySubmit;
use App\Services\AcademicServices;

class SenenController extends Controller
{
    private $deanService;

    public function __construct(AcademicServices $deanService)
    {
        $this->deanService = $deanService;
    }

    public function deanDashboard(Request $request)
    {
        
        $thisSchool = 0;
        if ($request->schoolSegmentation > 0) {
            $thisSchool = $request->schoolSegmentation;
        }

        $dropDown = $this->deanService->dropDown();
        $schoolInfo = $this->deanService->sections($thisSchool); 
        $dashboard = $this->deanService->dashboard($thisSchool);
        $lowerAndHigher = $this->deanService->lowerAndHigher($thisSchool);

        return view('Senen.SenenDashboard', compact("schoolInfo", "dashboard","dropDown","lowerAndHigher"));
    }

    public function deanSchools()
    {
        $school = [];
        $dataQuery = DB::table("schools as sc")
            ->join("courses as c", "sc.id", "=", "c.school_id")
            ->join("sections as sec", "c.id", "=", "sec.course_id")
            ->join("survey_submits as sb", "sec.id", "=", "sb.section_id")
            ->join("surveys as s", "sb.survey_id", "=", "s.id")
            ->join("response_submits as rs", "sb.id", "=", "rs.survey_submit_id")
            ->join("question_options as qo", "rs.question_option_id", "=", "qo.id")
            ->select(
                "sc.id as schoolId",
                "sc.name as schoolName",
                DB::raw('SUM(qo.calification) as totEscuela'),
                DB::raw('count(distinct(sb.id)) as Alumnos'),
            )
            ->where("s.status",1)
            ->groupBy('sc.id')
            ->get();

        $data = $dataQuery->values();

        foreach ($data as $item) {
            $school[] = [
                "id" => $item->schoolId,
                "Name" => $item->schoolName,
                "score" => round($item->totEscuela / $item->Alumnos),
            ];
        }

        return view('Senen.SenenSchools', compact("school"));
    }

    public function deanResults($schoolId)
    {
        $years = Survey::selectRAW("Year(dateStart)")
            ->distinct()
            ->get();
        $thisYear = session()->pull('year', now()->year);

        $data = DB::table('survey_submits as sb')
            ->join('response_submits as rs', 'sb.id', '=', 'rs.survey_submit_id')
            ->join('sections as sec', 'sb.section_id', '=', 'sec.id')
            ->join('courses as c', 'sec.course_id', '=', 'c.id')
            ->join('users as u', 'sb.user_id', '=', 'u.id')
            ->join('users as prof', 'sec.user_id', '=', 'prof.id')
            ->join('question_options as qo', 'rs.question_option_id', '=', 'qo.id')
            ->join('surveys as s', 'sb.survey_id', '=', 's.id')
            ->join("schools as sc","c.school_id","=","sc.id")
            ->where('c.school_id', $schoolId)
            ->whereYear('s.created_at', $thisYear)
            ->select(
                'prof.name as professorName',
                'prof.id as professorId',
                'c.name as courses',
                'sec.id as sectionId',
                "sc.Name as schoolName",
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
                    "sectionCode" => "placeholder",
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

        $schoolName = $data[0]->schoolName;

        return view('Senen.SenenResults', compact("dataResults", "schoolName", "schoolId","years"));
    }

    public function deanStudentView($sectionId)
    {
        $resultados = $this->deanService->studentView($sectionId); 
        return view('Senen.SenenStudentView', compact("resultados"));
    }

    public function deanViewAnswer($submitId)
    {
        return $this->deanService->viewAnswer($submitId); 
    }

    public function deanSchoolFilter(Request $request)
    {
        $school = $this->deanService->filterSchoolDean($request->schoolSearch);  
        return view('Senen.SenenSchools', compact("school"));
    }

    public function deanResultsFilter(Request $request)
    {
        $dataResults = $this->deanService->filterResults($request->all());
        if (!$dataResults) {
            return redirect()->back()->with('alert','No hay info en ese período.');
        }

        $schoolName = $dataResults[0]["schoolName"];
        $schoolId = $dataResults[0]["schoolId"];
        $years = Survey::selectRAW("Year(dateStart)")
            ->distinct()
            ->get();

        return view('Senen.SenenResults', compact("dataResults", "schoolName", "schoolId","years"));
    }

    public function deanLastFive()
    {
        return $this->deanService->lastFive();
    }

    public function deanSchoolPDF()
    {
        $schools = School::select("id")->get();
        $schoolsId = $schools->pluck("id")->toArray();
        $thisYear = now()->year;
        $surveysOfThisYear = Survey::whereYear("dateStart", $thisYear)->select("id")->get();
        $school = [];

        $dataQuery = DB::table("schools as sc")
            ->join("courses as c", "sc.id", "=", "c.school_id")
            ->join("sections as sec", "c.id", "=", "sec.course_id")
            ->join("survey_submits as sb", "sec.id", "=", "sb.section_id")
            ->join("surveys as s", "sb.survey_id", "=", "s.id")
            ->join("response_submits as rs", "sb.id", "=", "rs.survey_submit_id")
            ->join("question_options as qo", "rs.question_option_id", "=", "qo.id")
            ->whereIn('sc.id', $schoolsId)
            ->whereIn("s.id", $surveysOfThisYear)
            ->select(
                "sc.id as schoolId",
                "sc.name as schoolName",
                DB::raw('SUM(qo.calification) as totEscuela'),
                DB::raw('count(distinct(sb.id)) as Alumnos'),
            )
            ->groupBy('sc.id', 's.id')
            ->get();

        $data = $dataQuery->values();

        foreach ($data as $item) {
            $school[] = [
                "id" => $item->schoolId,
                "Name" => $item->schoolName,
                "score" => round($item->totEscuela / $item->Alumnos),
            ];
        }

        $pdf = Pdf::loadView('pdf.deanSchoolPDF', compact('school'));
        return $pdf->download('resultados-escuelas.pdf');
    }

    public function deanSchoolExcel()
    {
        $schools = School::select("id")->get();
        $schoolsId = $schools->pluck("id")->toArray();
        $thisYear = now()->year;
        $surveysOfThisYear = Survey::whereYear("dateStart", $thisYear)->select("id")->get();
        $school = [];

        $dataQuery = DB::table("schools as sc")
            ->join("courses as c", "sc.id", "=", "c.school_id")
            ->join("survey_submits as sb", "c.id", "=", "sb.course_id")
            ->join("surveys as s", "sb.survey_id", "=", "s.id")
            ->join("response_submits as rs", "sb.id", "=", "rs.survey_submit_id")
            ->join("question_options as qo", "rs.question_option_id", "=", "qo.id")
            ->whereIn('sc.id', $schoolsId)
            ->whereIn("s.id", $surveysOfThisYear)
            ->select(
                "sc.id as schoolId",
                "sc.name as schoolName",
                DB::raw('SUM(qo.calification) as totEscuela'),
                DB::raw('count(distinct(sb.id)) as Alumnos'),
            )
            ->groupBy('sc.id', 's.id')
            ->get();

        $data = $dataQuery->values();

        foreach ($data as $item) {
            $school[] = [
                "Name" => $item->schoolName,
                "score" => round($item->totEscuela / $item->Alumnos),
            ];
        }

        return Excel::download(new deanSchoolExcel($school), 'reporteDean-escuelas.xlsx');
    }
}