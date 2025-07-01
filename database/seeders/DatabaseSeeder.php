<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Survey;
use App\Models\QuestionGroup;
use App\Models\QuestionOption;
use App\Models\SurveySubmit;
use App\Models\ResponseSubmit;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\School;
use App\Models\Teacher;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
      
        
        User::create([
            'name' => 'Alumno 1',
            'email'=>"a@a.com",
            'role' => 'student',
        ]);

        User::create([
            'name' => 'Alumno 2',
            'email'=>"b@b.com",
            'role' => 'student',
        ]);

        User::create([
            'name' => 'Alumno 3',
            'email'=>"c@c.com",
            'role' => 'student',
        ]); 
        User::create([
            'name' => 'profesor 1',
            'email'=>"d@.com",
            'role' => 'professor',
        ]);
        User::create([
            'name' => 'profesor 2',
            'email'=>"e@e.com",
            'role' => 'professor',
        ]);
        User::create([
            'name' => 'profesor 3',
            'email'=>"f@f.com",
            'role' => 'professor',
        ]);

        User::create([
            'name' => 'Director 1',
            'email'=>"g@g.com",
            'role' => 'Director',
        ]);

        User::create([
            'name' => 'Director 2',
            'email'=>"h@h.com",
            'role' => 'Director',
        ]);

        User::create([
            'name' => 'Director 3',
            'email'=>"i@i.com",
            'role' => 'Director',
        ]);

        User::create([
            'name' => 'Admin 1',
            'email'=>"j@j.com",
            'role' => 'Admin',
        ]);
        
        User::create([
            'name' => 'Rector 1',
            'email'=>"k@k.com",
            'role' => 'Dean',
        ]);

        School::create([
            "name"=>" Escuela de Ciencias de la Computación",
            "director_id"=>7,
        ]);

        School::create([
            "name"=>" Escuela de Derecho",
            "director_id"=>8,
        ]);
        School::create([
            "name"=>"Escuela de Comunicación",
            "director_id"=>9,
        ]);

        Course::factory(30)->create();
        Enrollment::create([
            'course_id' => 1,
            'user_id' => 1,
        ]);

        
          Enrollment::create([
            'course_id' => 6,
            'user_id' => 1,
        ]);
        Enrollment::create([
            'course_id' => 1,
            'user_id' => 2,
        ]);
        Enrollment::create([
            'course_id' => 1,
            'user_id' => 3,
        ]);

Enrollment::create([
            'course_id' => 2,
            'user_id' => 1,
        ]);
        
        Enrollment::create([
            'course_id' => 5,
            'user_id' => 3,
        ]);
        Enrollment::create([
            'course_id' => 3,
            'user_id' => 2,
        ]);
Enrollment::create([
            'course_id' => 2,
            'user_id' => 2,
        ]);
    



        Survey::create([
            'revision' => 'Evaluacion #2 Mayo 2026',
            'dateStart' => '2026-05-12',
            'dateEnd' => '2026-06-12',
            'Author' => 'admin1',
            'term' => '1',
            'status' =>1,
        ]);

        
        QuestionGroup::create([
            'groupName'=>1,
            'survey_id' => 1,
        ]);
        QuestionGroup::create([
            'groupName'=>2,
            'survey_id' => 1,
        ]);
        QuestionGroup::create([
            'groupName'=>3,
            'survey_id' => 1,
        ]);

QuestionGroup::create([
            'groupName'=>4,
            'survey_id' => 1,
        ]);
        QuestionGroup::create([
            'groupName'=>5,
            'survey_id' => 1,
        ]);
        QuestionGroup::create([
            'groupName'=>6,
            'survey_id' => 1,
        ]);
        QuestionGroup::create([
            'groupName'=>7,
            'survey_id' => 1,
        ]);
        QuestionGroup::create([
            'groupName'=>8,
            'survey_id' => 1,
        ]);
        QuestionGroup::create([
            'groupName'=>9,
            'survey_id' => 1,
        ]);
        QuestionGroup::create([
            'groupName'=>10,
            'survey_id' => 1,
        ]);

        QuestionOption::create([
            'question_group_id'=>1,
            'calification'=>2,
            'option'=>"Se donde consultar mi material didactico",
        ]);
        
        QuestionOption::create([
            'question_group_id'=>1,
            'calification'=>0,
            'option'=>"Tengo que ser autodidacta la mayoria del tiempo",
        ]);
        
        QuestionOption::create([
            'question_group_id'=>2,
            'calification'=>2,
            'option'=>"Mi profesor me corrige de manera profesional",
        ]);

        
        QuestionOption::create([
            'question_group_id'=>2,
            'calification'=>0,
            'option'=>"Mi profesor me corrige de manera poco profesional",
        ]);

        
        QuestionOption::create([
            'question_group_id'=>3,
            'calification'=>2,
            'option'=>"Mis tareas son revisadas en tiempo y forma con su respectiva retroalimentacion.",
        ]);

        
        QuestionOption::create([
            'question_group_id'=>3,
            'calification'=>0,
            'option'=>"Mis tareas son revisadas en periodos irregualres y cuentan con poca retroalimentacion.",
        ]);

        QuestionOption::create([
            'question_group_id'=>4,
            'calification'=>2,
            'option'=>"Mis tareas son revisadas en periodos irregualres y cuentan con poca retroalimentacion.",
        ]);

        
        QuestionOption::create([
            'question_group_id'=>4,
            'calification'=>0,
            'option'=>"Mis tareas son revisadas en periodos irregualres y cuentan con poca retroalimentacion.",
        ]);

        
        QuestionOption::create([
            'question_group_id'=>5,
            'calification'=>2,
            'option'=>"Mis tareas son revisadas en periodos irregualres y cuentan con poca retroalimentacion.",
        ]);

        QuestionOption::create([
            'question_group_id'=>5,
            'calification'=>0,
            'option'=>"Mis tareas son revisadas en periodos irregualres y cuentan con poca retroalimentacion.",
        ]);

        
        QuestionOption::create([
            'question_group_id'=>6,
            'calification'=>2,
            'option'=>"Mis tareas son revisadas en periodos irregualres y cuentan con poca retroalimentacion.",
        ]);

        
        QuestionOption::create([
            'question_group_id'=>6,
            'calification'=>0,
            'option'=>"Mis tareas son revisadas en periodos irregualres y cuentan con poca retroalimentacion.",
        ]);

        
        QuestionOption::create([
            'question_group_id'=>7,
            'calification'=>2,
            'option'=>"Mis tareas son revisadas en periodos irregualres y cuentan con poca retroalimentacion.",
        ]);
        
        QuestionOption::create([
            'question_group_id'=>7,
            'calification'=>0,
            'option'=>"Mis tareas son revisadas en periodos irregualres y cuentan con poca retroalimentacion.",
        ]);
        
        QuestionOption::create([
            'question_group_id'=>8,
            'calification'=>2,
            'option'=>"Mis tareas son revisadas en periodos irregualres y cuentan con poca retroalimentacion.",
        ]);
        
        QuestionOption::create([
            'question_group_id'=>8,
            'calification'=>0,
            'option'=>"Mis tareas son revisadas en periodos irregualres y cuentan con poca retroalimentacion.",
        ]);

        QuestionOption::create([
            'question_group_id'=>9,
            'calification'=>2,
            'option'=>"Mis tareas son revisadas en periodos irregualres y cuentan con poca retroalimentacion.",
        ]);

        QuestionOption::create([
            'question_group_id'=>9,
            'calification'=>0,
            'option'=>"Mis tareas son revisadas en periodos irregualres y cuentan con poca retroalimentacion.",
        ]);

        
        QuestionOption::create([
            'question_group_id'=>10,
            'calification'=>2,
            'option'=>"Mis tareas son revisadas en periodos irregualres y cuentan con poca retroalimentacion.",
        ]);

        QuestionOption::create([
            'question_group_id'=>10,
            'calification'=>0,
            'option'=>"Mis tareas son revisadas en periodos irregualres y cuentan con poca retroalimentacion.",
        ]);

    }
}
