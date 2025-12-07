<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\School;
use App\Models\Course;
use App\Models\Section;
use App\Models\User;

use Illuminate\Http\Request;
use App\API\AcademicCharge;

class AcademicChargeController extends Controller
{
  protected AcademicCharge $academicCharge;

    public function __construct(AcademicCharge $academicCharge)
    {
        $this->academicCharge = $academicCharge;
    }
    public function charge()
    {

  DB::beginTransaction();
    try {
      
  $termInfo = $this->academicCharge->validatedTerm();
if ($termInfo["exists"])
{
  return response()->json("Ya se realizo la carga academica de este periodo");
     return redirect()->back()->with('alert','Ya se realizo la carga academico para este periodo.');
}

  $schoolsData = $this->academicCharge->getSchoolData(); 
  $termClassesData =$this->academicCharge->getTermClassesData($termInfo);
  $authorities = $this->academicCharge->getAuthorities(); 
  User::insert($authorities);
School::insert($schoolsData);
Course::insert($termClassesData["courses"]);
User::insert($termClassesData["professors"]);
Section::insert($termClassesData["sections"]);
DB::commit();
    }
     catch (\Exception $e) {
        DB::rollBack();
     return response()->json($e);


        return redirect()->back()->with('alert','Ha ocurrido un error durante la carga académica, por favor intente de nuevo.');
}
   return redirect()->back()->with('success','Carga académica realizada con éxito.');
    }
}

