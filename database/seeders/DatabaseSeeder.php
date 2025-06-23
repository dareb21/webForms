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
        Course::create([
            "name"=> "capto rine",
            'user_id'=>5
        ]);
        Course::factory(5)->create();
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
            'status' =>1,
        ]);

        
        QuestionGroup::create([
            'groupName'=>'Indicador 1',
            'survey_id' => 1,
        ]);
        QuestionGroup::create([
            'groupName'=>'Indicador 2',
            'survey_id' => 1,
        ]);
        QuestionGroup::create([
            'groupName'=>'Indicador 3',
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
            'user_id'=>1,
            'survey_id'=>1,
            'course_id'=>1,
            'observations'=>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse sit amet risus ut felis mollis porta id quis est. Cras vitae enim lectus. Ut non rhoncus est, non maximus urna. Vestibulum ut imperdiet diam. Nullam ligula lacus, maximus nec imperdiet maximus, convallis sit amet nibh. Phasellus aliquam scelerisque urna, id accumsan quam pharetra in. Praesent suscipit condimentum nisi. Ut lacinia facilisis lacus in pellentesque. Curabitur non lacus at orci feugiat pulvinar ac sit amet eros. Aenean interdum maximus nisi, a aliquet eros elementum vitae. Suspendisse venenatis neque at libero pellentesque, in cursus eros dapibus.",
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
