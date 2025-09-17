<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\School;
use App\Models\Course;
use App\Models\Section;
use App\Models\Professor;
use App\Models\Director;
use Illuminate\Http\Request;

class AcademicChargeController extends Controller
{
    public function charge()
    {
  DB::beginTransaction();
    try {

  $directorsInfo =[
    ['name'=>'Director 1','id'=>58],
    ['name'=>'Director 2','id'=>451],
    ['name'=>'Director 3','id'=>388],
    ['name'=>'Director 4','id'=>459],
    ['name'=>'Director 5','id'=>40],
    ['name'=>'Director 6','id'=>465],
    ['name'=>'Director 7','id'=>450],
    ['name'=>'Director 8','id'=>137],
    ['name'=>'Director 9','id'=>124],
    ['name'=>'Director 10','id'=>386],
    ['name'=>'Docencia','id'=>9999],
    ];

Director::insert($directorsInfo);
    
$schoolsApi = Http::get('https://melioris.usap.edu/api/evaldoc/v1/escuelas'); //Se obtiene la info de la api
$schoolsInfo = collect($schoolsApi->json()); //Se convierte a colección
$schools = $schoolsInfo
    ->map(function ($item) { //Se mapea la colección para obtener los datos necesarios
        return [
            'sigaId'            => $item['ID_ESCUELA'], // Se saca el id de la escuela
            'DESCRIPCION_ESCUELA' => $item['DESCRIPCION_ESCUELA'], // Se saca la descripción de la escuela
            'director_id'      => is_null($item['ID_USUARIO']) ? 9999 : $item['ID_USUARIO'], // Se saca el id del director
        ];
    })
    ->values() // Se obtienen los valores
    ->all(); // Se convierte a array
School::insert($schools); // Se insertan los datos en la tabla schools

$response = Http::get('https://melioris.usap.edu/api/evaldoc/v1/periodo-academico/2025-1/oferta-academica');
$chargeInfo = collect($response->json());
$uniqueCourses = $chargeInfo
    ->unique('ID_CURSO')
    ->map(function ($item) {
        return [
            'sigaId'            => $item['ID_CURSO'],
            'descripcion_curso' => $item['DESCRIPCION_CURSO'],
            'school_id'         => $item['ID_ESCUELA'],
        ];
    })
    ->values()
    ->all();
Course::insert($uniqueCourses);

$sectionsArray = collect($chargeInfo)->map(function ($item) {
    return [
        'sigaId'       => $item['ID_SECCION'],
        'course_id'    => $item['ID_CURSO'],
        'professor_id' => $item['CUENTA_CATEDRATICO'],
    ];
})->toArray();

$professorsArray = collect($chargeInfo)
    ->unique('CUENTA_CATEDRATICO') // elimina duplicados por cuenta
    ->map(function ($item) {
        return [
            'name'    => $item['NOMBRE_COMPLETO'],
            'account' => $item['CUENTA_CATEDRATICO'],
        ];
    })->toArray();
Professor::insert($professorsArray);
Section::insert($sectionsArray);

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