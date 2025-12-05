<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\School;
use App\Models\Course;
use App\Models\Section;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\AcademicCharge;

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
      
  $thisTermAPI = Http::get('https://melioris.usap.edu/api/evaldoc/v1/periodo-actual')->json();
  $thisTerm = $thisTermAPI[0]["periodo-actual"];

  $schoolsData = $this->academicCharge->getSchoolData(); 
  $termClassesData =$this->academicCharge->getTermClassesData($thisTerm);
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
     
        return redirect()->back()->with('alert','Ha ocurrido un error durante la carga académica, por favor intente de nuevo.');
}
   return redirect()->back()->with('success','Carga académica realizada con éxito.');
    }
}

