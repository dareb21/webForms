<?php
namespace App\Services;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

Class AcademicServices
{
   public function schools($thisSchool)
   {


$sections = DB::table('schools')
    ->join('courses', 'schools.id', '=', 'courses.school_id')
    ->join('sections', 'courses.id', '=', 'sections.course_id')
    ->leftJoin('survey_submits', 'sections.id', '=', 'survey_submits.section_id')
    ->when($thisSchool >0, function ($query) use ($thisSchool) {
        $query->where('schools.id', $thisSchool);
    })
    ->select(
        'schools.id as schoolsId',
        'schools.name as schoolsName',
        DB::raw('COUNT(DISTINCT sections.id) as section_count'),
        DB::raw('COUNT(DISTINCT CASE WHEN survey_submits.id IS NOT NULL THEN sections.id END) as sections_with_submits')
    )
    ->groupBy('schools.id')
    ->get();
$schoolsCollection=$sections->pluck("schoolsId","schoolsName");
$schools = $schoolsCollection->map(function ($id, $name) {
    return [
        'id' => $id,
        'name' => $name,
    ];
})->values()->toArray();
$allSections=$sections->sum("section_count");
$sectionsWithSubmits=$sections->sum("sections_with_submits");
return ["withSubmits" =>$sectionsWithSubmits,"sections" =>$allSections, "schoolsInfo" => $schools];
}


    public function dashboard($thisSchool)
    {

$thisYear = now()->year;
$data = DB::table("surveys as s")
    ->join("survey_submits as sb", "s.id", "=", "sb.survey_id")
    ->join("response_submits as rs", "sb.id", "=", "rs.survey_submit_id")
    ->join("question_options as qo", "rs.question_option_id", "=", "qo.id")
    ->when($thisSchool >0, function ($query) use ($thisSchool) {
        $query->join("sections as sec","sb.section_id","=","sec.id")
        ->join("courses as c","sec.course_id","=","c.id")
        ->join("schools as sc","c.school_id","sc.id")
        ->where('sc.id', $thisSchool);
    })
    ->whereYear("s.dateStart", $thisYear)
    ->select(
        "s.id as survey_id",
        DB::raw("SUM(qo.calification) as SumaNotaPeriodo"),
        DB::raw("COUNT(DISTINCT sb.id) as Divisor")
    )
    ->groupBy("s.id")
    ->get();

$infoPerTerm = collect();
$i = 1;

foreach ($data as $item) {
    $notaPeriodo = $item->Divisor == 0 ? 0 : round($item->SumaNotaPeriodo / $item->Divisor);
    $infoPerTerm->push([
        "termScore" => $notaPeriodo,
        "term" => $i,
    ]);
    $i++;
}

$anualScore = round($infoPerTerm->pluck("termScore")->sum() / max(count($infoPerTerm), 1));
$infoPerTermArray=$infoPerTerm->toArray();
return ["resultsPerTerm" =>$infoPerTermArray,"anual" =>$anualScore];

}
public function lowerAndHigher($thisSchool)
{
    
$lower10Query = DB::table('users as prof')
    ->join('sections as sec', 'prof.id', '=', 'sec.user_id')
    ->join('survey_submits as sb', 'sec.id', '=', 'sb.section_id')
    ->join('response_submits as rs', 'sb.id', '=', 'rs.survey_submit_id')
    ->join('question_options as qo', 'rs.question_option_id', '=', 'qo.id')
    ->join('surveys as s', 'sb.survey_id', '=', 's.id')
    ->select('prof.name', DB::raw('AVG(qo.calification) * 10 as Calification'))
    ->when($thisSchool >0, function ($query) use ($thisSchool) {
        $query->join("courses as c","sec.course_id","=","c.id")
        ->join("schools as sc", "c.school_id", "=", "sc.id")
        ->where('sc.id', $thisSchool);
    })
    ->groupBy('prof.id', 'prof.name')
    ->having('Calification', '<', 10)
    ->orderBy('Calification', 'asc')
    ->limit(10)
    ->get();

$higher15Query=DB::table('users as prof')
    ->join('sections as sec', 'prof.id', '=', 'sec.user_id')
    ->join('survey_submits as sb', 'sec.id', '=', 'sb.section_id')
    ->join('response_submits as rs', 'sb.id', '=', 'rs.survey_submit_id')
    ->join('question_options as qo', 'rs.question_option_id', '=', 'qo.id')
    ->join('surveys as s', 'sb.survey_id', '=', 's.id')
    ->select('prof.name', DB::raw('AVG(qo.calification) * 10 as Calification'))
    ->when($thisSchool >0, function ($query) use ($thisSchool) {
        $query->join("courses as c","sec.course_id","=","c.id")
        ->join("schools as sc", "c.school_id", "=", "sc.id")
        ->where('sc.id', $thisSchool);
    })
    ->groupBy('prof.id', 'prof.name')
    ->having('Calification', '>', 15)
    ->orderBy('Calification', 'desc')
    ->limit(10)
    ->get();

$higher15=$higher15Query->toArray();
$lower10=$lower10Query->toArray();

return ["lower10"=>$lower10,"higher15"=>$higher15];
}

public function results()
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
            ->whereYear('s.created_at',$thisYear)
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

if($data->isEmpty()){
  return $noInfo=True;
}
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
    return ["dataResults"=> $dataResults,"dataPaginate"=>$data];
}
public function studentView($sectionId)
{
    
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
            return $noInfo = True; 
        }

        foreach ($data as $item) {
            $resultados[] = [
                "score" => $item->scoreStudent,
                "profesor" => $item->professorName,
                "course" => $item->course,
                "nameStudent" => $item->student,  
                "submitId" => $item->submitId,
                "sectionCode" => $item->section,
            ];
        }
   return ['adminStudentView' => $resultados,'paginate' => $data];     
}

public function viewAnswer($submitId)
{
    $data = DB::table('question_groups as qg')
        ->join('question_options as qo', 'qg.id', '=', 'qo.question_group_id')
        ->join('response_submits as rs', 'qo.id', '=', 'rs.question_option_id')
        ->join('survey_submits as sb', 'rs.survey_submit_id', '=', 'sb.id')
        ->where('sb.id', $submitId)
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

}
