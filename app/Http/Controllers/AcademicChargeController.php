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
  
  return response()->json($termClassesData);

User::insert($authoritiesInfo);
School::insert($schools);
Course::insert($uniqueCourses);
User::insert($professorsArray);
Section::insert($sectionsArray);

DB::commit();
    }
     catch (\Exception $e) {
        DB::rollBack();
        return response()->json($e);

        return redirect()->back()->with('alert','Ha ocurrido un error durante la carga académica, por favor intente de nuevo.');
}
return response()->json('Carga académica realizada con éxito.');
    return redirect()->back()->with('success','Carga académica realizada con éxito.');
    }
}

