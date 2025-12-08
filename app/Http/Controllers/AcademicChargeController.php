<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;


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

  $termInfo = $this->academicCharge->validatedTerm();
if ($termInfo["exists"])
{
     return redirect()->back()->with('alert','Ya se realizo la carga academico para este periodo.');
}

  $schoolsData = $this->academicCharge->getSchoolData(); 
  $termClassesData =$this->academicCharge->getTermClassesData($termInfo);
  $authorities = $this->academicCharge->getAuthorities(); 

$succesfullUpdate = $this->academicCharge->updateCharge($authorities, $schoolsData, $termClassesData,$termInfo["newTermId"]);
if (!$succesfullUpdate)
{
    return redirect()->back()->with('alert','Ocurrio un error al actualizar la carga, intente nuevamente.'); 
}
   return redirect()->back()->with('success','Carga académica realizada con éxito.');
    }
}

