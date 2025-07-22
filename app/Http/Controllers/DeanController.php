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


class DeanController extends Controller
{
private $deanService;
  public function __construct(AcademicServices $deanService)
  {
      $this->deanService = $deanService;
  }

public function deanDashboard(Request $request)
    {
     $thisSchool = 0;
    if ($request->schoolSegmentation > 0)
    {
        $thisSchool = $request->schoolSegmentation;
    }
     $schoolInfo =$this->deanService->schools($thisSchool); 
    $dashboard = $this->deanService->dashboard($thisSchool);
    $lowerAndHigher = $this->deanService->lowerAndHigher($thisSchool);

return view('dean.deanDashboard', compact("schoolInfo", "dashboard", "lowerAndHigher"));

    }



    public function deanResults($schoolId)
    {
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
                'sec.code as sectionCode',
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
                    "sectionCode" => $i->sectionCode,
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
        $schoolName = $data->schoolName;

        return view('dean.deanResults', compact("dataResults", "schoolName", "schoolId"));
    }



    public function deanSchools()
    {
        $thisYear = now()->year;
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
            ->groupBy('sc.id')
            ->get();
    
        $data = $dataQuery->values();
        //PROBA CON UN MAP HACER ESTO
        foreach ($data as $item) {
            $school[] = [
                "id" => $item->schoolId,
                "Name" => $item->schoolName,
                "score" => round($item->totEscuela / $item->Alumnos),
            ];
        }

        return view('dean.deanSchools', compact("school"));
    }

    public function deanStudentView($sectionId)
    {
        

      $resultados =$this->deanService->studentView($sectionId); 

        return view('dean.deanStudentView', compact("resultados"));
    }

    public function deanViewAnswer($submitId)
    {
        return $this->deanService->viewAnswer($submitId); 
    }


    public function deanSchoolPDF()
    {
        $i = 0;
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
        //PROBA CON UN MAP HACER ESTO
        foreach ($data as $item) {
            $school[] = [
                "id" => $item->schoolId,
                "Name" => $item->schoolName,
                "score" => round($item->totEscuela / $item->Alumnos),
            ];

        }
        // Generar PDF
        $pdf = Pdf::loadView('pdf.deanSchoolPDF', compact('school'));
        return $pdf->download('resultados-escuelas.pdf');
    }

public function deanSchoolExcel() //ponerle parametro
{
    $i=0;
        $schools = School::select("id")->get(); //quitar en especifico
        $schoolsId = $schools->pluck("id")->toArray(); //quitar en especifico
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
        //PROBA CON UN MAP HACER ESTO
        foreach ($data as $item) {
            $school[] = [
                "Name" => $item->schoolName,
                "score" => round($item->totEscuela / $item->Alumnos),
            ];

        }

        return Excel::download(new deanSchoolExcel($school), 'reporteDean-escuelas.xlsx');
    }

    public function deanSchoolFilter(Request $request)
    {
        $thisYear = now()->year;
        $surveysOfThisYear = Survey::whereYear("dateStart", $thisYear)->select("id")->get();
        $school = [];
        $dataQuery = DB::table("schools as sc")
            ->join("courses as c", "sc.id", "=", "c.school_id")
            ->join("survey_submits as sb", "c.id", "=", "sb.course_id")
            ->join("surveys as s", "sb.survey_id", "=", "s.id")
            ->join("response_submits as rs", "sb.id", "=", "rs.survey_submit_id")
            ->join("question_options as qo", "rs.question_option_id", "=", "qo.id")
            ->where('sc.id', $request->schoolSearch)
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
        //PROBA CON UN MAP HACER ESTO
        foreach ($data as $item) {
            $school[] = [
                "id" => $item->schoolId,
                "Name" => $item->schoolName,
                "score" => round($item->totEscuela / $item->Alumnos),
            ];
        }
        return view('dean.deanSchools', compact("school"));
    }

    public function deanResultsFilter(Request $request)
    {
    if ( $request->anualPeriod == 4)
    {
      return redirect()->route("deanResults", ['schoolId' => $request->schoolId])->with('year', $request->anualYear);        
    }
 $thisSchool = School::with("courses.sections.professor")->findOrFail($request->schoolId);
 $schoolId = $thisSchool->id;
        //$professorId = ($thisSchool->courses->pluck("professor.id"))->toArray();
        $data = DB::table('survey_submits as sb')
    ->join('response_submits as rs', 'sb.id', '=', 'rs.survey_submit_id')
    ->join('sections as sec', 'sb.section_id', '=', 'sec.id')
    ->join('courses as c', 'sec.course_id', '=', 'c.id')
    ->join('users as u', 'sb.user_id', '=', 'u.id')
    ->join('users as prof', 'sec.user_id', '=', 'prof.id')
    ->join('question_options as qo', 'rs.question_option_id', '=', 'qo.id')
    ->join('surveys as s', 'sb.survey_id', '=', 's.id')
    ->when($request->filled("catedraticoBusqueda"), function ($q) use($request) { $q->where('u.name', 'like','%'. $request->catedraticoBusqueda . "%");})
            ->where('c.school_id', $thisSchool->id)
            ->whereYear('s.created_at', $request->anualYear)
            ->where('s.term', $request->anualPeriod)
            ->select(
                'prof.name as professorName',
                'prof.id as professorId',
                'c.name as courses',
                'sec.id as sectionId',
                'sec.code as sectionCode',
                DB::raw('SUM(qo.calification) as totSurvey'),
                DB::raw("COUNT(DISTINCT sb.id) AS totStudents"),
            )
            ->groupBy('prof.name', 'sec.id')
            ->paginate(10);
            //Esta es la partque que iria en el service
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
                    "sectionCode" => $i->sectionCode,
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
        $schoolName = $thisSchool->name;

        return view('dean.deanResults', compact("dataResults", "schoolName", "schoolId"));

    }
}



