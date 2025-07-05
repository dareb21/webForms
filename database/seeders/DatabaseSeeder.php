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
      
User::create(['name' => 'Andrea García', 'email' => 'andrea.garcia@gmail.com', 'role' => 'student']);
User::create(['name' => 'Luis Torres', 'email' => 'luis.torres@hotmail.com', 'role' => 'student']);
User::create(['name' => 'Camila Rojas', 'email' => 'camila.rojas@yahoo.com', 'role' => 'student']);
User::create(['name' => 'Pedro Díaz', 'email' => 'pedro.diaz@outlook.com', 'role' => 'student']);
User::create(['name' => 'Natalia Cruz', 'email' => 'natalia.cruz@gmail.com', 'role' => 'student']);
User::create(['name' => 'Emilio Varela', 'email' => 'emilio.varela@gmail.com', 'role' => 'student']);
User::create(['name' => 'Daniela Herrera', 'email' => 'daniela.herrera@gmail.com', 'role' => 'student']);
User::create(['name' => 'Javier Ortega', 'email' => 'javier.ortega@hotmail.com', 'role' => 'student']);
User::create(['name' => 'Claudia Rivera', 'email' => 'claudia.rivera@icloud.com', 'role' => 'student']);
User::create(['name' => 'Esteban Molina', 'email' => 'esteban.molina@gmail.com', 'role' => 'student']);
User::create(['name' => 'Valeria Ramos', 'email' => 'valeria.ramos@gmail.com', 'role' => 'student']);
User::create(['name' => 'Tomás Herrera', 'email' => 'tomas.herrera@yahoo.com', 'role' => 'student']);
User::create(['name' => 'Gabriela Luna', 'email' => 'gabriela.luna@gmail.com', 'role' => 'student']);
User::create(['name' => 'Samuel Cordero', 'email' => 'samuel.cordero@gmail.com', 'role' => 'student']);
User::create(['name' => 'Melisa Pérez', 'email' => 'melisa.perez@outlook.com', 'role' => 'student']);
User::create(['name' => 'Martín Silva', 'email' => 'martin.silva@gmail.com', 'role' => 'student']);
User::create(['name' => 'Isabella Méndez', 'email' => 'isabella.mendez@gmail.com', 'role' => 'student']);
User::create(['name' => 'Ángel Romero', 'email' => 'angel.romero@hotmail.com', 'role' => 'student']);
User::create(['name' => 'Renata Ponce', 'email' => 'renata.ponce@gmail.com', 'role' => 'student']);
User::create(['name' => 'Sebastián Aguilar', 'email' => 'sebastian.aguilar@gmail.com', 'role' => 'student']);
User::create(['name' => 'Alejandra Mejía', 'email' => 'alejandra.mejia@gmail.com', 'role' => 'student']);
User::create(['name' => 'Rodrigo Castillo', 'email' => 'rodrigo.castillo@outlook.com', 'role' => 'student']);
User::create(['name' => 'Lucía Moreno', 'email' => 'lucia.moreno@gmail.com', 'role' => 'student']);
User::create(['name' => 'David Jiménez', 'email' => 'david.jimenez@gmail.com', 'role' => 'student']);
User::create(['name' => 'Sara Navarro', 'email' => 'sara.navarro@gmail.com', 'role' => 'student']);
User::create(['name' => 'Leonardo Fuentes', 'email' => 'leonardo.fuentes@hotmail.com', 'role' => 'student']);
User::create(['name' => 'Paula Cabrera', 'email' => 'paula.cabrera@gmail.com', 'role' => 'student']);
User::create(['name' => 'Matías Soto', 'email' => 'matias.soto@gmail.com', 'role' => 'student']);
User::create(['name' => 'Regina Lozano', 'email' => 'regina.lozano@yahoo.com', 'role' => 'student']);
User::create(['name' => 'Cristian Palma', 'email' => 'cristian.palma@gmail.com', 'role' => 'student']);

//seeder profesores
User::create(['name' => 'Ana Torres', 'email' => 'ana.torres@gmail.com', 'role' => 'professor']);
User::create(['name' => 'Luis Martínez', 'email' => 'luis.martinez@hotmail.com', 'role' => 'professor']);
User::create(['name' => 'María López', 'email' => 'maria.lopez@yahoo.com', 'role' => 'professor']);
User::create(['name' => 'Carlos Gómez', 'email' => 'carlos.gomez@outlook.com', 'role' => 'professor']);
User::create(['name' => 'Laura Rodríguez', 'email' => 'laura.rdz@gmail.com', 'role' => 'professor']);
User::create(['name' => 'José Hernández', 'email' => 'jose.hdz@gmail.com', 'role' => 'professor']);
User::create(['name' => 'Fernanda Ramírez', 'email' => 'fernanda.ramirez@gmail.com', 'role' => 'professor']);
User::create(['name' => 'Diego Ruiz', 'email' => 'diego.ruiz@hotmail.com', 'role' => 'professor']);
User::create(['name' => 'Daniela Castro', 'email' => 'daniela.castro@icloud.com', 'role' => 'professor']);
User::create(['name' => 'Andrés Morales', 'email' => 'andres.morales@gmail.com', 'role' => 'professor']);
User::create(['name' => 'Sofía Vega', 'email' => 'sofia.vega@gmail.com', 'role' => 'professor']);
User::create(['name' => 'Raúl Méndez', 'email' => 'raul.mendez@yahoo.com', 'role' => 'professor']);
User::create(['name' => 'Carmen Silva', 'email' => 'carmen.silva@gmail.com', 'role' => 'professor']);
User::create(['name' => 'Marco Antonio', 'email' => 'marco.antonio@gmail.com', 'role' => 'professor']);
User::create(['name' => 'Valeria Soto', 'email' => 'valeria.soto@outlook.com', 'role' => 'professor']);
User::create(['name' => 'Ricardo Peña', 'email' => 'ricardo.pena@gmail.com', 'role' => 'professor']);
User::create(['name' => 'Paola Carrillo', 'email' => 'paola.carrillo@gmail.com', 'role' => 'professor']);
User::create(['name' => 'Héctor Salinas', 'email' => 'hector.salinas@hotmail.com', 'role' => 'professor']);
User::create(['name' => 'Natalia Mendoza', 'email' => 'natalia.mendoza@gmail.com', 'role' => 'professor']);
User::create(['name' => 'Jorge Paredes', 'email' => 'jorge.paredes@gmail.com', 'role' => 'professor']);
User::create(['name' => 'Mónica Reyes', 'email' => 'monica.reyes@gmail.com', 'role' => 'professor']);
User::create(['name' => 'Gabriel Duarte', 'email' => 'gabriel.duarte@outlook.com', 'role' => 'professor']);
User::create(['name' => 'Lucía Navarro', 'email' => 'lucia.navarro@gmail.com', 'role' => 'professor']);
User::create(['name' => 'Iván Ríos', 'email' => 'ivan.rios@gmail.com', 'role' => 'professor']);
User::create(['name' => 'Brenda Ortiz', 'email' => 'brenda.ortiz@gmail.com', 'role' => 'professor']);
User::create(['name' => 'Roberto Acosta', 'email' => 'roberto.acosta@hotmail.com', 'role' => 'professor']);
User::create(['name' => 'Isabel Cordero', 'email' => 'isabel.cordero@gmail.com', 'role' => 'professor']);
User::create(['name' => 'Alonso Villanueva', 'email' => 'alonso.villanueva@gmail.com', 'role' => 'professor']);
User::create(['name' => 'Julia Aguilar', 'email' => 'julia.aguilar@yahoo.com', 'role' => 'professor']);
User::create(['name' => 'Manuel Chávez', 'email' => 'manuel.chavez@gmail.com', 'role' => 'professor']);


User::create(['name' => 'María González', 'email' => 'maria.gonzalez@escuela.com', 'role' => 'Director']);//61
User::create(['name' => 'Juan Pérez', 'email' => 'juan.perez@escuela.com', 'role' => 'Director']);
User::create(['name' => 'Laura Ramírez', 'email' => 'laura.ramirez@escuela.com', 'role' => 'Director']);
User::create(['name' => 'Carlos Méndez', 'email' => 'carlos.mendez@escuela.com', 'role' => 'Director']);
User::create(['name' => 'Isabel Torres', 'email' => 'isabel.torres@escuela.com', 'role' => 'Director']);
User::create(['name' => 'Luis Moreno', 'email' => 'luis.moreno@escuela.com', 'role' => 'Director']);
User::create(['name' => 'Carmen Díaz', 'email' => 'carmen.diaz@escuela.com', 'role' => 'Director']);
User::create(['name' => 'Andrés Castillo', 'email' => 'andres.castillo@escuela.com', 'role' => 'Director']);
User::create(['name' => 'Patricia Flores', 'email' => 'patricia.flores@escuela.com', 'role' => 'Director']);
User::create(['name' => 'Fernando Herrera', 'email' => 'fernando.herrera@escuela.com', 'role' => 'Director']);

User::create(['name' => 'Rigoberto Padilla', 'email'=>"Rigoberto@Padilla.com",'role' => 'Admin',]);
        
User::create(['name' => 'Juan Euceda','email'=>"Juan@Euceda.com",'role' => 'Dean',
        ]);

School::create(['name' => 'Escuela de Ciencias de la Computación', 'director_id' => 61]);
School::create(['name' => 'Escuela de Derecho', 'director_id' => 62]);
School::create(['name' => 'Escuela de Comunicación', 'director_id' => 63]);
School::create(['name' => 'Escuela de Ciencias Económicas', 'director_id' => 64]);
School::create(['name' => 'Escuela de Ingeniería Civil', 'director_id' => 65]);
School::create(['name' => 'Escuela de Psicología', 'director_id' => 66]);
School::create(['name' => 'Escuela de Arquitectura', 'director_id' => 67]);
School::create(['name' => 'Escuela de Educación', 'director_id' => 68]);
School::create(['name' => 'Escuela de Biología', 'director_id' => 69]);
School::create(['name' => 'Escuela de Matemáticas', 'director_id' => 70]);

//seeder cursos
$schoolNames = [
    'Ciencias de la Computación',
    'Derecho',
    'Comunicación',
    'Ciencias Económicas',
    'Ingeniería Civil',
    'Psicología',
    'Arquitectura',
    'Educación',
    'Biología',
    'Matemáticas',
];

// Asumiendo que ya existen las escuelas y están en el mismo orden que el arreglo
$schools = School::orderBy('id')->take(10)->get();
$professorIds = User::where('role', 'professor')->pluck('id')->toArray();

$classesBySchool = [
    // Computación
    ['Estructuras de Datos', 'Bases de Datos', 'Algoritmos', 'Programación I', 'Programación II', 'Redes', 'Sistemas Operativos', 'Inteligencia Artificial', 'Ingeniería de Software', 'Desarrollo Web'],

    // Derecho
    ['Derecho Constitucional', 'Derecho Penal', 'Derecho Civil', 'Teoría del Estado', 'Derecho Mercantil', 'Derecho Internacional', 'Derechos Humanos', 'Criminología', 'Código Procesal Penal', 'Ética Jurídica'],

    // Comunicación
    ['Periodismo', 'Redacción Profesional', 'Teoría de la Comunicación', 'Medios Digitales', 'Comunicación Organizacional', 'Publicidad', 'Radio y Televisión', 'Diseño Gráfico', 'Producción Audiovisual', 'Fotografía'],

    // Ciencias Económicas
    ['Contabilidad I', 'Economía General', 'Microeconomía', 'Macroeconomía', 'Estadística I', 'Matemáticas Financieras', 'Gestión Empresarial', 'Mercadeo', 'Costos', 'Finanzas'],

    // Ingeniería Civil
    ['Estática', 'Resistencia de Materiales', 'Dibujo Técnico', 'Topografía', 'Estructuras', 'Construcción I', 'Construcción II', 'Hidráulica', 'Hormigón Armado', 'Diseño Estructural'],

    // Psicología
    ['Psicología General', 'Neuropsicología', 'Psicología del Desarrollo', 'Teorías de la Personalidad', 'Psicología Clínica', 'Psicopatología', 'Evaluación Psicológica', 'Psicología Educativa', 'Psicología Social', 'Terapias Cognitivas'],

    // Arquitectura
    ['Diseño Arquitectónico', 'Historia de la Arquitectura', 'Construcción y Materiales', 'Dibujo Arquitectónico', 'Urbanismo', 'Diseño Bioclimático', 'Modelado 3D', 'Taller de Maquetas', 'Tecnología de la Construcción', 'Paisajismo'],

    // Educación
    ['Didáctica General', 'Planeación Educativa', 'Evaluación del Aprendizaje', 'Psicología Educativa', 'Filosofía de la Educación', 'Currículo', 'Gestión Educativa', 'TIC en la Educación', 'Educación Inclusiva', 'Metodología de la Investigación'],

    // Biología
    ['Biología Celular', 'Genética', 'Microbiología', 'Botánica', 'Zoología', 'Ecología', 'Fisiología Animal', 'Biología Molecular', 'Biotecnología', 'Bioquímica'],

    // Matemáticas
    ['Álgebra', 'Geometría', 'Cálculo I', 'Cálculo II', 'Estadística', 'Matemática Discreta', 'Ecuaciones Diferenciales', 'Matemática Aplicada', 'Probabilidades', 'Teoría de Números'],
];

// Crear las clases
foreach ($schools as $index => $school) {
    $classList = $classesBySchool[$index];
    
    foreach ($classList as $className) {
        Course::create([
            'name' => $className,
            'user_id' => fake()->randomElement($professorIds),
            'status' =>1,
            'school_id' => $school->id,
        ]);
    }
}

//seeder Matricula
    $studentIds = User::where('role', 'student')->pluck('id')->toArray();
     $courseIds = Course::pluck('id')->toArray();

    foreach ($studentIds as $studentId) {
        $selectedCourses = collect($courseIds)->random(rand(1, 6));
        foreach ($selectedCourses as $courseId) {
            Enrollment::create([
                'course_id' => $courseId,
                'user_id' => $studentId,
            ]);
            }
        }
     
    
        
Enrollment::create([
    "course_id" =>88,
    'user_id' => 7,
]);
 Survey::create([
            'revision' => 'Evaluacion #2 Mayo 2026',
            'dateStart' => '2025-10-12',
            'dateEnd' => '2025-11-12',
            'Author' => 'admin1',
            'term' => '1',
            'status' =>1,
        ]);
//seeder question group
for ($i=1; $i<=20;$i++)
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

        for ($i = 1; $i <= 20; $i++) {
            $pos = $positiveOptions[array_rand($positiveOptions)];
            $neg = $negativeOptions[array_rand($negativeOptions)];

            QuestionOption::create([
                'question_group_id' => $i,
                'calification' => 1,
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
