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
$currentTermAPI = Http::get('https://melioris.usap.edu/api/evaldoc/v1/periodo-actual');
$currentTermJSON = $currentTermAPI->json();
$currentTerm= $currentTermJSON[0]["periodo-actual"];

$authoritiesApi = Http::get('https://melioris.usap.edu/api/evaldoc/v1/autoridades'); 
$authoritiesJson = collect($authoritiesApi->json());
$authoritiesInfo = $authoritiesJson
    ->map(function ($item) { 
        return [
            'id'            =>  $item['id_usuario'], // Se saca el id del usuario
            'name' => $item['nombre_usuario'], // Se saca el nombre completo
            'email' => $item['email'],
            'role'      => $item['rol_usuario'], // Se asigna el rol de director
        ];
    })
    ->values() 
    ->all(); 
    
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
 // Se insertan los datos en la tabla schools
                                                                                
$response = Http::get('https://melioris.usap.edu/api/evaldoc/v1/periodo-academico/' . $currentTerm . '/oferta-academica');
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
$sectionsArray = collect($chargeInfo)->map(function ($item) {
    return [
        'id'       => $item['ID_SECCION'],
        'course_id'    => $item['ID_CURSO'],
        'user_id' => $item['CUENTA_CATEDRATICO'],
        'dayHour' => $item['HORARIO_SECCION'],
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

