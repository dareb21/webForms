<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\School;
use App\Models\Course;
use App\Models\Section;
use App\Models\User;
use Illuminate\Http\Request;

class AcademicChargeController extends Controller
{
    public function charge()
    {
  DB::beginTransaction();
    try {

  $directorsInfo =[
    ['name'=>'Juan Gabriel Garcia','email'=>'juan.garcia@usap.edu','id'=>58,'role'=>'Director de Escuela'],
    ['name'=>'Director 2','id'=>451,'email'=>null,'role'=>'Director de Escuela'],
    ['name'=>'Director 3','id'=>388,'email'=>null,'role'=>'Director de Escuela'],
    ['name'=>'Director 4','id'=>459,'email'=>null,'role'=>'Director de Escuela'],
    ['name'=>'Director 5','id'=>40,'email'=>null,'role'=>'Director de Escuela'],
    ['name'=>'Director 6','id'=>465,'email'=>null,'role'=>'Director de Escuela'],
    ['name'=>'Director 7','id'=>450,'email'=>null,'role'=>'Director de Escuela'],
    ['name'=>'Director 8','id'=>137,'email'=>null,'role'=>'Director de Escuela'],
    ['name'=>'Director 9','id'=>124,'email'=>null,'role'=>'Director de Escuela'],
    ['name'=>'Director 10','id'=>386,'email'=>null,'role'=>'Director de Escuela'],
    ['name'=>'Rigoberto Paz','id'=>9999,'email'=>'rigoberto.paz@usap.edu','role'=>'Director de Docencia'],
    ['name'=>'Juan Euceda','id'=>9998,'email'=>'juan.euceda@usap.edu','role'=>'Decano de Facultad'],
    ];

User::insert($directorsInfo);
    
$schoolsApi = Http::get('https://melioris.usap.edu/api/evaldoc/v1/escuelas'); //Se obtiene la info de la api
$schoolsInfo = collect($schoolsApi->json()); //Se convierte a colección
$schools = $schoolsInfo
    ->map(function ($item) { //Se mapea la colección para obtener los datos necesarios
        return [
            'id'            => $item['ID_ESCUELA'], // Se saca el id de la escuela
            'name' => $item['DESCRIPCION_ESCUELA'], // Se saca la descripción de la escuela
            'director_id'      => is_null($item['ID_USUARIO']) ? 9999 : $item['ID_USUARIO'], // Se saca el id del director
        ];
    })
    ->values() // Se obtienen los valores
    ->all(); // Se convierte a array
School::insert($schools); // Se insertan los datos en la tabla schools

$response = Http::get('https://melioris.usap.edu/api/evaldoc/v1/periodo-academico/2025-2/oferta-academica');
$chargeInfo = collect($response->json());
$uniqueCourses = $chargeInfo
    ->unique('ID_CURSO')
    ->map(function ($item) {
        return [
            'id'            => $item['ID_CURSO'],
            'name' => $item['DESCRIPCION_CURSO'],
            'school_id'         => $item['ID_ESCUELA'],
        ];
    })
    ->values()
    ->all();
Course::insert($uniqueCourses);

$sectionsArray = collect($chargeInfo)->map(function ($item) {
    return [
        'id'       => $item['ID_SECCION'],
        'course_id'    => $item['ID_CURSO'],
        'user_id' => $item['CUENTA_CATEDRATICO'],
    ];
})->toArray();

$professorsArray = collect($chargeInfo)
    ->unique('CUENTA_CATEDRATICO') // elimina duplicados por cuenta
    ->map(function ($item) {
        return [
            'name'    => $item['NOMBRE_COMPLETO'],
            'id' => $item['CUENTA_CATEDRATICO'],
            'role' => 'Catedrático',
        ];
    })->toArray();
User::insert($professorsArray);
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

