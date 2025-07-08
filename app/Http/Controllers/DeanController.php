<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Survey;
use Illuminate\Support\Facades\DB;


class DeanController extends Controller
{
   public function deanHome()
    {
        $user = (object) session('google_user');
        return view("decano/decanoDashboard",compact("user"));
        
    }

    public function deanDashboard(){
        return view('dean.deanDashboard');
    }

    public function deanResults(){
        return view('dean.deanResults');
    }

    public function deanSchools(){
        $i=0;
        $schools = School::select("Name","id")->get();
        $schoolsName =$schools->pluck("Name")->toArray(); 
        $schoolsId = $schools->pluck("id")->toArray();
        $thisYear = now()->year;
        $surveysOfThisYear=Survey::whereYear("created_at",$thisYear)->select("id")->get();
        $school =[];

    $data = DB::table("schools as sc")
    ->join("courses as c","sc.id","=","c.school_id")
    ->join("survey_submits as sb","c.id","=","sb.course_id")
    ->join("surveys as s","sb.survey_id","=","s.id")
    ->join("response_submits as rs","sb.id","=","rs.survey_submit_id")
    ->join("question_options as qo", "rs.question_option_id","=","qo.id")
    ->whereIn('sc.id', $schoolsId)
    ->where("s.id",1)
    ->select(
        DB::raw('SUM(qo.calification) as totEscuela'),
        DB::raw('count(distinct(sb.user_id)) as Alumnos'),
    )
    ->groupBy('sc.id', 's.id')
    ->get();
foreach ($data as $item)
{
    $totSchool=$data->pluck("totEscuela") ; 
    $totlStudents=$data->pluck("Alumnos");
 $school[] =[
    "id" =>$schoolsId[$i],
    "Name" => $schoolsName[$i],
    "score" =>   round($totSchool[$i] /  $totlStudents[$i]) ,
 ];
 $i+=1;
}
return view('dean.deanSchools',compact("school"));
    }

    public function deanStudentView(){
        return view('dean.deanStudentView');
    }
}
