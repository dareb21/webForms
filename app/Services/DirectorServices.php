<?php
namespace App\Services;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

Class DirectorServices
{
   public function sections($directorId)
   {
$sections = DB::table('schools')
    ->join('courses', 'schools.id', '=', 'courses.school_id')
    ->join('sections', 'courses.id', '=', 'sections.course_id')
    ->leftJoin('survey_submits', 'sections.id', '=', 'survey_submits.section_id')
    ->where('schools.director_id', $directorId)
    ->select(
        DB::raw('COUNT(DISTINCT sections.id) as section_count'),
        DB::raw('COUNT(DISTINCT CASE WHEN survey_submits.id IS NOT NULL THEN sections.id END) as sections_with_submits')
    )
    ->groupBy('schools.id')
    ->get();
$allSections=$sections->sum("section_count");
$sectionsWithSubmits=$sections->sum("sections_with_submits");
$sectionsLeft = $allSections -$sectionsWithSubmits;
return ["withSubmits" =>$sectionsWithSubmits,"sections" =>$allSections];
}


    public function dashboard($directorId)
    {
    $data = DB::table("surveys as s")
    ->join("survey_submits as sb", "s.id", "=", "sb.survey_id")
    ->join("response_submits as rs", "sb.id", "=", "rs.survey_submit_id")
    ->join("question_options as qo", "rs.question_option_id", "=", "qo.id")
    ->join("sections as sec","sb.section_id","=","sec.id")
    ->join("courses as c","sec.course_id","=","c.id")
    ->join("schools as sc","c.school_id","sc.id")
    ->whereYear("s.dateStart", now()->year)
    ->where("sc.director_id",$directorId)
    ->select(
        "s.id as survey_id",
        DB::raw("SUM(qo.calification) as SumaNotaPeriodo"),
        DB::raw("COUNT(DISTINCT sb.id) as Divisor")
    )
    ->groupBy("s.id")
    ->get();
    
    $numerador=$data->pluck("SumaNotaPeriodo");
    $divisor=$data->pluck("Divisor");

    $infoPerTerm = collect();
    $i=1;
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

public function higherOrLower($directorId)
{
$lower10Query = DB::table('users as prof')
    ->join('sections as sec', 'prof.id', '=', 'sec.user_id')
    ->join('survey_submits as sb', 'sec.id', '=', 'sb.section_id')
    ->join('response_submits as rs', 'sb.id', '=', 'rs.survey_submit_id')
    ->join('question_options as qo', 'rs.question_option_id', '=', 'qo.id')
    ->join('surveys as s', 'sb.survey_id', '=', 's.id')
    ->join("courses as c","sec.course_id","=","c.id")
    ->join("schools as sc", "c.school_id", "=", "sc.id")
    ->where('sc.director_id', $directorId)
        ->select('prof.name', DB::raw('AVG(qo.calification) * 10 as Calification'))
    ->groupBy('prof.id', 'prof.name')
    ->having('Calification', '<', 10)
    ->orderBy('Calification', 'asc')
    ->limit(10)
    ->get();

$higher15Query = DB::table('users as prof')
    ->join('sections as sec', 'prof.id', '=', 'sec.user_id')
    ->join('survey_submits as sb', 'sec.id', '=', 'sb.section_id')
    ->join('response_submits as rs', 'sb.id', '=', 'rs.survey_submit_id')
    ->join('question_options as qo', 'rs.question_option_id', '=', 'qo.id')
    ->join('surveys as s', 'sb.survey_id', '=', 's.id')
    ->join("courses as c","sec.course_id","=","c.id")
    ->join("schools as sc", "c.school_id", "=", "sc.id")
    ->where('sc.director_id', $directorId)
        ->select('prof.name', DB::raw('AVG(qo.calification) * 10 as Calification'))
    ->groupBy('prof.id', 'prof.name')
    ->having('Calification', '>', 15)
    ->orderBy('Calification', 'desc')
    ->limit(10)
    ->get();

$higher15=$higher15Query->toArray();
$lower10=$lower10Query->toArray();

return ["lower10"=>$lower10,"higher15"=>$higher15];
}

public function filter(Array $request)
{
    
    $dataResults = [];
    $thisProfessor= $request["catedraticoBusqueda"] ?? null ;
    $thisYear = $request["annualYear"] ?? null;
    $thisTerm = $request["annualPeriod"] ?? null;
    $data = DB::table('survey_submits as sb')
            ->join('response_submits as rs', 'sb.id', '=', 'rs.survey_submit_id')
            ->join('sections as sec', 'sb.section_id', '=', 'sec.id')
            ->join('courses as c', 'sec.course_id', '=', 'c.id')
            ->join("schools as sc","c.school_id","=","sc.id")
            ->join('users as prof', 'sec.user_id', '=', 'prof.id')
            ->join('question_options as qo', 'rs.question_option_id', '=', 'qo.id')
            ->join('surveys as s', 'sb.survey_id', '=', 's.id') 
            ->when($thisProfessor, function ($query) use ($thisProfessor) {
                $query->where('prof.name', 'like', '%' . $thisProfessor . '%');
            })
            ->when($thisYear, function ($query) use ($thisYear) {
                $query->whereYear('s.dateStart', $thisYear);        
             })
            ->when($thisTerm && $thisTerm>0, function ($query) use ($thisTerm) {
                $query->where('s.term', $thisTerm);
            })

            ->where('sc.director_id', Auth()->id()) 
            ->select(
                'prof.name as professorName',
                'prof.id as professorId',
                'c.name as courses',
                'sec.id as sectionId',
                "sec.schedule as schedule",
                DB::raw('SUM(qo.calification) as totSurvey'),
                DB::raw("COUNT(DISTINCT sb.id) AS totStudents"),
            )
            ->groupBy('prof.id', 'sec.id')
            ->paginate(10);
            if ($data->isEmpty())
         {
            return False;       
            }
        $professors = $data->groupBy('professorId');
        foreach($professors as $professor => $sections) 
         {
         $coursesData = $sections->map(function ($i) {
             $totPerCourse = round($i->totSurvey / $i->totStudents);
                return [
                "sectionId" => $i->sectionId,
                "sectionCode" => $i->schedule,
                "course" => $i->courses,
                "totPerCourse" => $totPerCourse
            ];            
      });
    $dataResults[] = [
        "professorName" => $sections->first()->professorName,
        "professorScoreAvg" =>  round($sections->pluck("totSurvey")->sum()  / $sections->pluck("totStudents")->sum()),
        "coursesData" => $coursesData->toArray(),
    ];
        } 
return collect($dataResults);
}

public function lastFive()
{

}
}
