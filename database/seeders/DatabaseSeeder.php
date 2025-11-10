<?php

namespace Database\Seeders;

use App\Models\Survey;
use App\Models\QuestionGroup;
use App\Models\QuestionOption;
use App\Models\SurveySubmit;
use App\Models\ResponseSubmit;
use App\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
/*
        $roles = [
    ["role" => "Alumno"],
    ["role" => "Director de Docencia"],
    ["role" => "Director de Escuela"],
    ["role" => "Decano de Facultad"],
    ["role" => "DCA"],
];

Role::insert($roles);
*/
 Survey::create([
            'revision' => 'Evaluacion #1 Enero 2025',
            'dateStart' => '2025-01-12',
            'dateEnd' => '2025-04-12',
            'Author' => 'Rigoberto Paz',
            'term' => '1',
            'status' =>0,
        ]);
        
//seeder question group
for ($i=1; $i<=10;$i++)
{
    QuestionGroup::create([
            'groupName'=>$i,
            'survey_id' => 1,
        ]);
    
}
 
//seeder question option
 $positiveOptions = [
            "Sé dónde consultar mi material didáctico",
            "El contenido de la clase es claro",
            "El profesor responde a mis dudas",
            "Las instrucciones de las tareas son comprensibles",
            "Puedo acceder fácilmente a los recursos del curso",
            "Me siento motivado para participar en clase",
            "El material complementa bien la clase",
            "Entiendo los temas tratados",
            "Las actividades ayudan a reforzar lo aprendido",
            "Puedo organizar mi tiempo para estudiar",
        ];

        $negativeOptions = [
            "Tengo que ser autodidacta la mayoría del tiempo",
            "La clase no tiene estructura clara",
            "No recibo respuesta a mis dudas",
            "Las tareas son confusas",
            "No encuentro los recursos que necesito",
            "No tengo interés en la clase",
            "El material no me ayuda mucho",
            "No comprendo los temas",
            "Las actividades no sirven de mucho",
            "No sé cómo administrar mi tiempo",
        ];

        for ($i = 1; $i <= 10; $i++) {
            $pos = $positiveOptions[array_rand($positiveOptions)];
            $neg = $negativeOptions[array_rand($negativeOptions)];

            QuestionOption::create([
                'question_group_id' => $i,
                'calification' => 2,
                'option' => $pos,
            ]);

            QuestionOption::create([
                'question_group_id' => $i,
                'calification' => 0,
                'option' => $neg,
            ]);
        }

    }
}
