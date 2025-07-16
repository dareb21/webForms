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


class DeanController extends Controller
{

    public function deanDashboard()
    {
        $i = 1;
        $resultados = collect();
        $thisYear = now()->year;
        $surveysOfThisYear = Survey::whereYear("dateStart", $thisYear)->select("id")->get();
        foreach ($surveysOfThisYear as $survey) {
            $data = DB::table("surveys as s")
                ->join("survey_submits as sb", "s.id", "=", "sb.survey_id")
                ->join("response_submits as rs", "sb.id", "=", "rs.survey_submit_id")
                ->join("question_options as qo", "rs.question_option_id", "=", "qo.id")
                ->where('s.id', $survey->id)
                ->select(
                    DB::raw('SUM(qo.calification) as SumaNotaPeriodo'),
                    DB::raw('count(distinct(sb.id)) as Divisor'),
                )
                ->first();
            if (!$data) {
                $notaperiodo = 0;
            } else {
                $numerador = $data->SumaNotaPeriodo;
                $divisor = $data->Divisor;
                $notaperiodo = round($numerador / $divisor);

                $resultados->push([
                    "termScore" => $notaperiodo,
                    "term" => $i,
                ]);
                $i += 1;
            }

        }
        $anual = round(($resultados->pluck("termScore"))->sum() / count($surveysOfThisYear));
        $allProfessor = User::where("role", "professor")->count();
        $amountProfessors = Section::has('submits')->get();
        $professorsEvaluated = $amountProfessors->pluck("user_id")->unique()->count();
        return view('dean.deanDashboard', compact("resultados", "anual", "allProfessor", "professorsEvaluated"));
    }



    public function deanResults($schoolId)
    {
        $thisYear = session()->pull('year', now()->year);
        $thisSchool = School::with("courses.sections.professor")->findOrFail($schoolId);
        //$professorId = ($thisSchool->courses->pluck("professor.id"))->toArray();
        $data = DB::table('survey_submits as sb')
            ->join('response_submits as rs', 'sb.id', '=', 'rs.survey_submit_id')
            ->join('sections as sec', 'sb.section_id', '=', 'sec.id')
            ->join('courses as c', 'sec.course_id', '=', 'c.id')
            ->join('users as u', 'sb.user_id', '=', 'u.id')
            ->join('users as prof', 'sec.user_id', '=', 'prof.id')
            ->join('question_options as qo', 'rs.question_option_id', '=', 'qo.id')
            ->join('surveys as s', 'sb.survey_id', '=', 's.id')
            ->where('c.school_id', $thisSchool->id)
            ->whereYear('s.created_at', $thisYear)
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



    public function deanSchools()
    {
        //Esta 2 primeras busquedas se pueden optimizar con un inner join
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
        $section = Section::with("professor", "course")->find($sectionId);
        $profesor = $section->professor->name;
        $courseName = $section->course->name;

        $data = DB::table('survey_submits as sb')
            ->join('response_submits as rs', 'sb.id', '=', 'rs.survey_submit_id')
            ->join('sections as sec', 'sb.section_id', '=', 'sec.id')
            ->join("courses as c", "sec.course_id", "=", "c.id")
            ->join('users as u', 'sb.user_id', '=', 'u.id')
            ->join('users as prof', 'sec.user_id', '=', 'prof.id')
            ->join('question_options as qo', 'rs.question_option_id', '=', 'qo.id')
            ->join('surveys as s', 'sb.survey_id', '=', 's.id')
            ->where('sec.id', $sectionId)
            ->whereYear('s.created_at', now()->year)
            ->select(
                'c.name as course',
                'sec.code as section',
                'sb.id as submitId',
                'prof.name as professorName',
                'u.name as student',
                DB::raw('SUM(qo.calification) as scoreStudent'),
            )
            ->groupBy('prof.name', 'c.name', 'submitId')
            ->paginate(10);
        if ($data->isEmpty()) {
            $noInfo = True;
            return view('dean.deanStudentView', compact("noInfo"));

        }
        foreach ($data as $item) {
            $resultados[] = [
                "score" => $item->scoreStudent,
                "profesor" => $profesor,
                "course" => $courseName,
                "nameStudent" => $item->student,
                "submitId" => $item->submitId,
            ];
        }


        return view('dean.deanStudentView', compact("resultados"));
    }

    public function deanViewAnswer($submitId)
    {
        $submit = SurveySubmit::findOrFail($submitId);
        $data = DB::table('surveys as s')
            ->join('question_groups as qg', 's.id', '=', 'qg.survey_id')
            ->join('question_options as qo', 'qg.id', '=', 'qo.question_group_id')
            ->join('response_submits as rs', 'qo.id', '=', 'rs.question_option_id')
            ->join('survey_submits as sb', 'rs.survey_submit_id', '=', 'sb.id')
            ->join('sections as sec', 'sb.section_id', '=', 'sec.id')
            ->join('users as u', 'sb.user_id', '=', 'u.id')
            ->where('s.id', $submit->survey_id)
            ->where('u.id', $submit->user_id)
            ->where('sec.id', $submit->section_id)
            ->select(
                'qg.groupName as indicator',
                'qo.option as answer',
                'sb.observations as observation'
            )
            ->distinct()
            ->orderBy('qg.groupName')
            ->get();
        foreach ($data as $item) {
            $answer[] = [
                "indicator" => $item->indicator,
                "answer" => $item->answer,
            ];
        }
        $answer[] = [
            "observation" => $data[0]->observation,
        ];
        return $answer;
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

    public function deanSchoolExcel()
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



