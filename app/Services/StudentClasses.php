<?php
namespace App\Services;
use App\Models\Section;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
Class StudentClasses
{
public function getClasses(String $email)
{
    $sectionsAPI = Http::get("https://melioris.usap.edu/api/evaldoc/v1/estudiantes/" . $email ."/secciones");
    
    $sections = collect($sectionsAPI->json());
    
                $sectionId    = $sections->pluck('id');
            $teacher = $sections->pluck('NOMBRE_CATEDRATICO');
            $studentClasses=Section::join("courses","sections.course_id","courses.id")
            ->join("users","sections.user_id","users.id")
            ->whereIn("sections.id",$sectionId)
            ->where("sections.status",1)
            ->select("sections.id as sectionId","courses.name as courseName","users.name as teacherName")
            ->get();
            return $studentClasses;

    }         
 }           