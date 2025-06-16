<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Survey;
use App\Models\QuestionGroup;
use App\Models\QuestionOption;
use App\Models\SurveySubmit;
use App\Models\ResponseSubmit;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Survey::create([
            'name' => 'Evaluacion #2 Mayo 2026',
            'dateStart' => '2026-05-12',
            'dateEnd' => '2026-06-12',
            'Author' => 'admin1',
        ]);

        
        QuestionGroup::create([
            'survey_id' => 1,
        ]);
        QuestionGroup::create([
            'survey_id' => 1,
        ]);
        QuestionGroup::create([
            'survey_id' => 1,
        ]);

        QuestionOption::create([
            'question_group_id'=>1,
            'option'=>"Se donde consultar mi material didactico",
        ]);
        
        QuestionOption::create([
            'question_group_id'=>1,
            'option'=>"Tengo que ser autodidacta la mayoria del tiempo",
        ]);
        
        QuestionOption::create([
            'question_group_id'=>2,
            'option'=>"Mi profesor me corrige de manera profesional",
        ]);

        
        QuestionOption::create([
            'question_group_id'=>2,
            'option'=>"Mi profesor me corrige de manera poco profesional",
        ]);

        
        QuestionOption::create([
            'question_group_id'=>3,
            'option'=>"Mis tareas son revisadas en tiempo y forma con su respectiva retroalimentacion.",
        ]);

        
        QuestionOption::create([
            'question_group_id'=>3,
            'option'=>"Mis tareas son revisadas en periodos irregualres y cuentan con poca retroalimentacion.",
        ]);

        SurveySubmit::create([
            'survey_id'=>1,
            'DateSubmmited'=>"2026-05-23",
        ]);

        ResponseSubmit::create([
            'survey_submit_id'=>1,
            'question_option_id'=>1,
        ]);

        ResponseSubmit::create([
            'survey_submit_id'=>1,
            'question_option_id'=>4,
        ]);

        
        ResponseSubmit::create([
            'survey_submit_id'=>1,
            'question_option_id'=>5,
        ]);
    }
}
