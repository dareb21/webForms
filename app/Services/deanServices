<?php
namespace App\Services;

Class deanServices
{
public function showSchool()
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
}

