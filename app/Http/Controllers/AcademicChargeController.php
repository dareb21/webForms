<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\School;
use App\Models\Course;
use App\Models\Section;
use Illuminate\Http\Request;

class AcademicChargeController extends Controller
{
    public function charge()
    {
  DB::beginTransaction();
    try {
$schoolsApi = Http::get('https://melioris.usap.edu/api/evaldoc/v1/escuelas');
$schoolsInfo = collect($schoolsApi->json());
$schools = $schoolsInfo
    ->map(function ($item) {
        return [
            'sigaId'            => $item['ID_ESCUELA'],
            'DESCRIPCION_ESCUELA' => $item['DESCRIPCION_ESCUELA'],
        ];
    })
    ->values()
    ->all();
School::insert($schools);

$response = Http::get('https://melioris.usap.edu/api/evaldoc/v1/periodo-academico/2025-2/oferta-academica');
$chargeInfo = collect($response->json());
$uniqueCourses = $chargeInfo
    ->unique('ID_CURSO')
    ->map(function ($item) {
        return [
            'sigaId'            => $item['ID_CURSO'],
            'descripcion_curso' => $item['DESCRIPCION_CURSO'],
        ];
    })
    ->values()
    ->all();
Course::insert($uniqueCourses);

$sections=$chargeInfo->map(function ($item){
    return [
        "sigaId" => $item["ID_SECCION"],
        "course_id"=>$item['ID_CURSO']
    ];
})
->values()
->all();
Section::insert($sections);
DB::commit();
return response()->json("Carga académica cargada con éxito");
    }
     catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            "error" => "Ocurrió un error en la carga académica",
            "message" => $e->getMessage()
        ], 500);
    }
}
}