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
use App\Models\Section;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
      
$students = [
    ['name' => 'Paula Ramos', 'email' => 'paula.ramos0@example.com', 'role' => 'student'],
    ['name' => 'Jessica Varela', 'email' => 'jessica.varela1@example.com', 'role' => 'student'],
    ['name' => 'Cristian Torres', 'email' => 'cristian.torres2@example.com', 'role' => 'student'],
    ['name' => 'Alejandra Duarte', 'email' => 'alejandra.duarte3@example.com', 'role' => 'student'],
    ['name' => 'Ana Varela', 'email' => 'ana.varela4@example.com', 'role' => 'student'],
    ['name' => 'Daniela Vargas', 'email' => 'daniela.vargas5@example.com', 'role' => 'student'],
    ['name' => 'Valeria Cordero', 'email' => 'valeria.cordero6@example.com', 'role' => 'student'],
    ['name' => 'Luis Guzmán', 'email' => 'luis.guzmán7@example.com', 'role' => 'student'],
    ['name' => 'Ximena Molina', 'email' => 'ximena.molina8@example.com', 'role' => 'student'],
    ['name' => 'Melisa Jiménez', 'email' => 'melisa.jiménez9@example.com', 'role' => 'student'],
    ['name' => 'Diego Salinas', 'email' => 'diego.salinas10@example.com', 'role' => 'student'],
    ['name' => 'Brenda Paredes', 'email' => 'brenda.paredes11@example.com', 'role' => 'student'],
    ['name' => 'Valentina Silva', 'email' => 'valentina.silva12@example.com', 'role' => 'student'],
    ['name' => 'Carmen Palma', 'email' => 'carmen.palma13@example.com', 'role' => 'student'],
    ['name' => 'Oscar Ramírez', 'email' => 'oscar.ramírez14@example.com', 'role' => 'student'],
    ['name' => 'Renata Hernández', 'email' => 'renata.hernández15@example.com', 'role' => 'student'],
    ['name' => 'Marco Peña', 'email' => 'marco.peña16@example.com', 'role' => 'student'],
    ['name' => 'Tamara Cabrera', 'email' => 'tamara.cabrera17@example.com', 'role' => 'student'],
    ['name' => 'Clara Silva', 'email' => 'clara.silva18@example.com', 'role' => 'student'],
    ['name' => 'Pedro Martínez', 'email' => 'pedro.martínez19@example.com', 'role' => 'student'],
    ['name' => 'Valentina Pineda', 'email' => 'valentina.pineda20@example.com', 'role' => 'student'],
    ['name' => 'Clara León', 'email' => 'clara.león21@example.com', 'role' => 'student'],
    ['name' => 'Regina Reyes', 'email' => 'regina.reyes22@example.com', 'role' => 'student'],
    ['name' => 'Jorge Navarro', 'email' => 'jorge.navarro23@example.com', 'role' => 'student'],
    ['name' => 'David Soto', 'email' => 'david.soto24@example.com', 'role' => 'student'],
    ['name' => 'Kevin Paz', 'email' => 'kevin.paz25@example.com', 'role' => 'student'],
    ['name' => 'Julia Luna', 'email' => 'julia.luna26@example.com', 'role' => 'student'],
    ['name' => 'Samuel Lozano', 'email' => 'samuel.lozano27@example.com', 'role' => 'student'],
    ['name' => 'Constanza Cabrera', 'email' => 'constanza.cabrera28@example.com', 'role' => 'student'],
    ['name' => 'Emiliano Campos', 'email' => 'emiliano.campos29@example.com', 'role' => 'student'],
    ['name' => 'Clara Cabrera', 'email' => 'clara.cabrera30@example.com', 'role' => 'student'],
    ['name' => 'David Lozano', 'email' => 'david.lozano31@example.com', 'role' => 'student'],
    ['name' => 'Florencia Cruz', 'email' => 'florencia.cruz32@example.com', 'role' => 'student'],
    ['name' => 'Rocío Reyes', 'email' => 'rocío.reyes33@example.com', 'role' => 'student'],
    ['name' => 'Andrés Rivera', 'email' => 'andrés.rivera34@example.com', 'role' => 'student'],
    ['name' => 'Alonso Luna', 'email' => 'alonso.luna35@example.com', 'role' => 'student'],
    ['name' => 'Tomás Silva', 'email' => 'tomás.silva36@example.com', 'role' => 'student'],
    ['name' => 'David Soto', 'email' => 'david.soto37@example.com', 'role' => 'student'],
    ['name' => 'Julia Paz', 'email' => 'julia.paz38@example.com', 'role' => 'student'],
    ['name' => 'Rocío Navarro', 'email' => 'rocío.navarro39@example.com', 'role' => 'student'],
    ['name' => 'Eva Acosta', 'email' => 'eva.acosta40@example.com', 'role' => 'student'],
    ['name' => 'Mónica Vargas', 'email' => 'mónica.vargas41@example.com', 'role' => 'student'],
    ['name' => 'Laura Morales', 'email' => 'laura.morales42@example.com', 'role' => 'student'],
    ['name' => 'Leonardo Romero', 'email' => 'leonardo.romero43@example.com', 'role' => 'student'],
    ['name' => 'Sara Rojas', 'email' => 'sara.rojas44@example.com', 'role' => 'student'],
    ['name' => 'Matías Ponce', 'email' => 'matías.ponce45@example.com', 'role' => 'student'],
    ['name' => 'Melisa Moreno', 'email' => 'melisa.moreno46@example.com', 'role' => 'student'],
    ['name' => 'Eva Villanueva', 'email' => 'eva.villanueva47@example.com', 'role' => 'student'],
    ['name' => 'Isabella Moreno', 'email' => 'isabella.moreno48@example.com', 'role' => 'student'],
    ['name' => 'Renata Cabrera', 'email' => 'renata.cabrera49@example.com', 'role' => 'student'],
    ['name' => 'Oscar Torres', 'email' => 'oscar.torres50@example.com', 'role' => 'student'],
    ['name' => 'Pedro Vega', 'email' => 'pedro.vega51@example.com', 'role' => 'student'],
    ['name' => 'Clara Ramírez', 'email' => 'clara.ramírez52@example.com', 'role' => 'student'],
    ['name' => 'Javier León', 'email' => 'javier.león53@example.com', 'role' => 'student'],
    ['name' => 'Pedro Rivera', 'email' => 'pedro.rivera54@example.com', 'role' => 'student'],
    ['name' => 'Rodrigo Molina', 'email' => 'rodrigo.molina55@example.com', 'role' => 'student'],
    ['name' => 'Esteban Moreno', 'email' => 'esteban.moreno56@example.com', 'role' => 'student'],
    ['name' => 'Emilio Pineda', 'email' => 'emilio.pineda57@example.com', 'role' => 'student'],
    ['name' => 'Rocío Castillo', 'email' => 'rocío.castillo58@example.com', 'role' => 'student'],
    ['name' => 'Ángel Fuentes', 'email' => 'ángel.fuentes59@example.com', 'role' => 'student'],
    ['name' => 'Gabriel León', 'email' => 'gabriel.león60@example.com', 'role' => 'student'],
    ['name' => 'Valeria León', 'email' => 'valeria.león61@example.com', 'role' => 'student'],
    ['name' => 'Emilio Duarte', 'email' => 'emilio.duarte62@example.com', 'role' => 'student'],
    ['name' => 'Regina Salgado', 'email' => 'regina.salgado63@example.com', 'role' => 'student'],
    ['name' => 'Brenda Méndez', 'email' => 'brenda.méndez64@example.com', 'role' => 'student'],
    ['name' => 'Clara Rojas', 'email' => 'clara.rojas65@example.com', 'role' => 'student'],
    ['name' => 'Javier Díaz', 'email' => 'javier.díaz66@example.com', 'role' => 'student'],
    ['name' => 'Lucía Reyes', 'email' => 'lucía.reyes67@example.com', 'role' => 'student'],
    ['name' => 'Clara Soto', 'email' => 'clara.soto68@example.com', 'role' => 'student'],
    ['name' => 'Daniela Guzmán', 'email' => 'daniela.guzmán69@example.com', 'role' => 'student'],
    ['name' => 'Patricia Cruz', 'email' => 'patricia.cruz70@example.com', 'role' => 'student'],
    ['name' => 'Manuel Palma', 'email' => 'manuel.palma71@example.com', 'role' => 'student'],
    ['name' => 'Rodrigo Soto', 'email' => 'rodrigo.soto72@example.com', 'role' => 'student'],
    ['name' => 'Lucía Vargas', 'email' => 'lucía.vargas73@example.com', 'role' => 'student'],
    ['name' => 'Andrea Moreno', 'email' => 'andrea.moreno74@example.com', 'role' => 'student'],
    ['name' => 'Fernanda Palma', 'email' => 'fernanda.palma75@example.com', 'role' => 'student'],
    ['name' => 'Kevin Torres', 'email' => 'kevin.torres76@example.com', 'role' => 'student'],
    ['name' => 'Julia Vargas', 'email' => 'julia.vargas77@example.com', 'role' => 'student'],
    ['name' => 'David Vega', 'email' => 'david.vega78@example.com', 'role' => 'student'],
    ['name' => 'José Torres', 'email' => 'josé.torres79@example.com', 'role' => 'student'],
    ['name' => 'Mónica Ponce', 'email' => 'mónica.ponce80@example.com', 'role' => 'student'],
    ['name' => 'Oscar Díaz', 'email' => 'oscar.díaz81@example.com', 'role' => 'student'],
    ['name' => 'Oscar Cordero', 'email' => 'oscar.cordero82@example.com', 'role' => 'student'],
    ['name' => 'Bianca Acosta', 'email' => 'bianca.acosta83@example.com', 'role' => 'student'],
    ['name' => 'Roberto Cruz', 'email' => 'roberto.cruz84@example.com', 'role' => 'student'],
    ['name' => 'Raúl Rojas', 'email' => 'raúl.rojas85@example.com', 'role' => 'student'],
    ['name' => 'Ricardo Morales', 'email' => 'ricardo.morales86@example.com', 'role' => 'student'],
    ['name' => 'Lucía Morales', 'email' => 'lucía.morales87@example.com', 'role' => 'student'],
    ['name' => 'Rodrigo Miranda', 'email' => 'rodrigo.miranda88@example.com', 'role' => 'student'],
    ['name' => 'Samuel Antonio', 'email' => 'samuel.antonio89@example.com', 'role' => 'student'],
    ['name' => 'Noelia González', 'email' => 'noelia.gonzález90@example.com', 'role' => 'student'],
    ['name' => 'Tomás Varela', 'email' => 'tomás.varela91@example.com', 'role' => 'student'],
    ['name' => 'Melisa León', 'email' => 'melisa.león92@example.com', 'role' => 'student'],
    ['name' => 'Andrés León', 'email' => 'andrés.león93@example.com', 'role' => 'student'],
    ['name' => 'Laura Paz', 'email' => 'laura.paz94@example.com', 'role' => 'student'],
    ['name' => 'Marina Mejía', 'email' => 'marina.mejía95@example.com', 'role' => 'student'],
    ['name' => 'Renata Reyes', 'email' => 'renata.reyes96@example.com', 'role' => 'student'],
    ['name' => 'Natalia Fuentes', 'email' => 'natalia.fuentes97@example.com', 'role' => 'student'],
    ['name' => 'Julia Campos', 'email' => 'julia.campos98@example.com', 'role' => 'student'],
    ['name' => 'Esteban Salgado', 'email' => 'esteban.salgado99@example.com', 'role' => 'student'],
    ['name' => 'Sebastián Castro', 'email' => 'sebastián.castro100@example.com', 'role' => 'student'],
    ['name' => 'Emiliano Ríos', 'email' => 'emiliano.ríos101@example.com', 'role' => 'student'],
    ['name' => 'Isabel Duarte', 'email' => 'isabel.duarte102@example.com', 'role' => 'student'],
    ['name' => 'Cristian Martínez', 'email' => 'cristian.martínez103@example.com', 'role' => 'student'],
    ['name' => 'Melisa Castillo', 'email' => 'melisa.castillo104@example.com', 'role' => 'student'],
    ['name' => 'Samuel Jiménez', 'email' => 'samuel.jiménez105@example.com', 'role' => 'student'],
    ['name' => 'Melisa Cortés', 'email' => 'melisa.cortés106@example.com', 'role' => 'student'],
    ['name' => 'Samuel Rivas', 'email' => 'samuel.rivas107@example.com', 'role' => 'student'],
    ['name' => 'Gabriel Moreno', 'email' => 'gabriel.moreno108@example.com', 'role' => 'student'],
    ['name' => 'Matías Molina', 'email' => 'matías.molina109@example.com', 'role' => 'student'],
    ['name' => 'Andrea Antonio', 'email' => 'andrea.antonio110@example.com', 'role' => 'student'],
    ['name' => 'Ángel Mejía', 'email' => 'ángel.mejía111@example.com', 'role' => 'student'],
    ['name' => 'Marina Jiménez', 'email' => 'marina.jiménez112@example.com', 'role' => 'student'],
    ['name' => 'Valeria Pérez', 'email' => 'valeria.pérez113@example.com', 'role' => 'student'],
    ['name' => 'Diana Rodríguez', 'email' => 'diana.rodríguez114@example.com', 'role' => 'student'],
    ['name' => 'Martín Acosta', 'email' => 'martín.acosta115@example.com', 'role' => 'student'],
    ['name' => 'Martín Navarro', 'email' => 'martín.navarro116@example.com', 'role' => 'student'],
    ['name' => 'Natalia Soto', 'email' => 'natalia.soto117@example.com', 'role' => 'student'],
    ['name' => 'Gabriela Carrillo', 'email' => 'gabriela.carrillo118@example.com', 'role' => 'student'],
    ['name' => 'Natalia Palma', 'email' => 'natalia.palma119@example.com', 'role' => 'student'],
    ['name' => 'Ana Rodríguez', 'email' => 'ana.rodríguez120@example.com', 'role' => 'student'],
    ['name' => 'Camila León', 'email' => 'camila.león121@example.com', 'role' => 'student'],
    ['name' => 'Mónica Ríos', 'email' => 'mónica.ríos122@example.com', 'role' => 'student'],
    ['name' => 'Ana Molina', 'email' => 'ana.molina123@example.com', 'role' => 'student'],
    ['name' => 'Lucía Salinas', 'email' => 'lucía.salinas124@example.com', 'role' => 'student'],
    ['name' => 'Oscar Martínez', 'email' => 'oscar.martínez125@example.com', 'role' => 'student'],
    ['name' => 'Daniela Peña', 'email' => 'daniela.peña126@example.com', 'role' => 'student'],
    ['name' => 'Andrea Villanueva', 'email' => 'andrea.villanueva127@example.com', 'role' => 'student'],
    ['name' => 'Bianca Duarte', 'email' => 'bianca.duarte128@example.com', 'role' => 'student'],
    ['name' => 'Lucía Carrillo', 'email' => 'lucía.carrillo129@example.com', 'role' => 'student'],
    ['name' => 'Iván Rodríguez', 'email' => 'iván.rodríguez130@example.com', 'role' => 'student'],
    ['name' => 'Matías Ruiz', 'email' => 'matías.ruiz131@example.com', 'role' => 'student'],
    ['name' => 'Valentina Salinas', 'email' => 'valentina.salinas132@example.com', 'role' => 'student'],
    ['name' => 'Emiliano Duarte', 'email' => 'emiliano.duarte133@example.com', 'role' => 'student'],
    ['name' => 'Iván Ríos', 'email' => 'iván.ríos134@example.com', 'role' => 'student'],
    ['name' => 'Ana Duarte', 'email' => 'ana.duarte135@example.com', 'role' => 'student'],
    ['name' => 'Rocío Soto', 'email' => 'rocío.soto136@example.com', 'role' => 'student'],
    ['name' => 'Renata Romero', 'email' => 'renata.romero137@example.com', 'role' => 'student'],
    ['name' => 'Sofía Molina', 'email' => 'sofía.molina138@example.com', 'role' => 'student'],
    ['name' => 'Cristian López', 'email' => 'cristian.lópez139@example.com', 'role' => 'student'],
    ['name' => 'Natalia Silva', 'email' => 'natalia.silva140@example.com', 'role' => 'student'],
    ['name' => 'Héctor Romero', 'email' => 'héctor.romero141@example.com', 'role' => 'student'],
    ['name' => 'Valeria Ponce', 'email' => 'valeria.ponce142@example.com', 'role' => 'student'],
    ['name' => 'Sara Peña', 'email' => 'sara.peña143@example.com', 'role' => 'student'],
    ['name' => 'Héctor Aguilar', 'email' => 'héctor.aguilar144@example.com', 'role' => 'student'],
    ['name' => 'Sebastián Ramírez', 'email' => 'sebastián.ramírez145@example.com', 'role' => 'student'],
    ['name' => 'Diego Cruz', 'email' => 'diego.cruz146@example.com', 'role' => 'student'],
    ['name' => 'Matías Ríos', 'email' => 'matías.ríos147@example.com', 'role' => 'student'],
    ['name' => 'José Paz', 'email' => 'josé.paz148@example.com', 'role' => 'student'],
    ['name' => 'Javier Rojas', 'email' => 'javier.rojas149@example.com', 'role' => 'student'],
    ['name' => 'Laura Jiménez', 'email' => 'laura.jiménez150@example.com', 'role' => 'student'],
    ['name' => 'Renata Herrera', 'email' => 'renata.herrera151@example.com', 'role' => 'student'],
    ['name' => 'Kevin Mendoza', 'email' => 'kevin.mendoza152@example.com', 'role' => 'student'],
    ['name' => 'Melisa Paz', 'email' => 'melisa.paz153@example.com', 'role' => 'student'],
    ['name' => 'Clara Ríos', 'email' => 'clara.ríos154@example.com', 'role' => 'student'],
    ['name' => 'Daniela Pinto', 'email' => 'daniela.pinto155@example.com', 'role' => 'student'],
    ['name' => 'Alonso Cruz', 'email' => 'alonso.cruz156@example.com', 'role' => 'student'],
    ['name' => 'Julia Vargas', 'email' => 'julia.vargas157@example.com', 'role' => 'student'],
    ['name' => 'Cecilia Castro', 'email' => 'cecilia.castro158@example.com', 'role' => 'student'],
    ['name' => 'David Salinas', 'email' => 'david.salinas159@example.com', 'role' => 'student'],
    ['name' => 'Alejandra Mendoza', 'email' => 'alejandra.mendoza160@example.com', 'role' => 'student'],
    ['name' => 'Diana Hernández', 'email' => 'diana.hernández161@example.com', 'role' => 'student'],
    ['name' => 'Mónica Peña', 'email' => 'mónica.peña162@example.com', 'role' => 'student'],
    ['name' => 'Isabel Salas', 'email' => 'isabel.salas163@example.com', 'role' => 'student'],
    ['name' => 'Isabella Cordero', 'email' => 'isabella.cordero164@example.com', 'role' => 'student'],
    ['name' => 'Leonardo Paredes', 'email' => 'leonardo.paredes165@example.com', 'role' => 'student'],
    ['name' => 'Mónica Salas', 'email' => 'mónica.salas166@example.com', 'role' => 'student'],
    ['name' => 'Julia Duarte', 'email' => 'julia.duarte167@example.com', 'role' => 'student'],
    ['name' => 'Marco Cabrera', 'email' => 'marco.cabrera168@example.com', 'role' => 'student'],
    ['name' => 'Eva Cabrera', 'email' => 'eva.cabrera169@example.com', 'role' => 'student'],
    ['name' => 'Julieta Guzmán', 'email' => 'julieta.guzmán170@example.com', 'role' => 'student'],
    ['name' => 'Raúl Cordero', 'email' => 'raúl.cordero171@example.com', 'role' => 'student'],
    ['name' => 'Elena Ortiz', 'email' => 'elena.ortiz172@example.com', 'role' => 'student'],
    ['name' => 'Javier Pinto', 'email' => 'javier.pinto173@example.com', 'role' => 'student'],
    ['name' => 'Renata Herrera', 'email' => 'renata.herrera174@example.com', 'role' => 'student'],
    ['name' => 'Pedro Gómez', 'email' => 'pedro.gómez175@example.com', 'role' => 'student'],
    ['name' => 'Andrés Navarro', 'email' => 'andrés.navarro176@example.com', 'role' => 'student'],
    ['name' => 'Diana Molina', 'email' => 'diana.molina177@example.com', 'role' => 'student'],
    ['name' => 'Mónica Cordero', 'email' => 'mónica.cordero178@example.com', 'role' => 'student'],
    ['name' => 'Sebastián Peña', 'email' => 'sebastián.peña179@example.com', 'role' => 'student'],
    ['name' => 'Javier Ramos', 'email' => 'javier.ramos180@example.com', 'role' => 'student'],
    ['name' => 'Sofía Ruiz', 'email' => 'sofía.ruiz181@example.com', 'role' => 'student'],
    ['name' => 'Emilio Mejía', 'email' => 'emilio.mejía182@example.com', 'role' => 'student'],
    ['name' => 'Florencia Hernández', 'email' => 'florencia.hernández183@example.com', 'role' => 'student'],
    ['name' => 'Brenda Moreno', 'email' => 'brenda.moreno184@example.com', 'role' => 'student'],
    ['name' => 'Héctor Guzmán', 'email' => 'héctor.guzmán185@example.com', 'role' => 'student'],
    ['name' => 'Eva Cordero', 'email' => 'eva.cordero186@example.com', 'role' => 'student'],
    ['name' => 'Esteban Chávez', 'email' => 'esteban.chávez187@example.com', 'role' => 'student'],
    ['name' => 'Clara Miranda', 'email' => 'clara.miranda188@example.com', 'role' => 'student'],
    ['name' => 'Alonso Rodríguez', 'email' => 'alonso.rodríguez189@example.com', 'role' => 'student'],
    ['name' => 'Clara Duarte', 'email' => 'clara.duarte190@example.com', 'role' => 'student'],
    ['name' => 'Paola Ríos', 'email' => 'paola.ríos191@example.com', 'role' => 'student'],
    ['name' => 'Constanza Chávez', 'email' => 'constanza.chávez192@example.com', 'role' => 'student'],
    ['name' => 'Ricardo Hernández', 'email' => 'ricardo.hernández193@example.com', 'role' => 'student'],
    ['name' => 'Ximena Cordero', 'email' => 'ximena.cordero194@example.com', 'role' => 'student'],
    ['name' => 'Tamara Ruiz', 'email' => 'tamara.ruiz195@example.com', 'role' => 'student'],
    ['name' => 'David Villanueva', 'email' => 'david.villanueva196@example.com', 'role' => 'student'],
    ['name' => 'Clara Silva', 'email' => 'clara.silva197@example.com', 'role' => 'student'],
    ['name' => 'Laura Rodríguez', 'email' => 'laura.rodríguez198@example.com', 'role' => 'student'],
    ['name' => 'Sofía Salas', 'email' => 'sofía.salas199@example.com', 'role' => 'student'],
    ['name' => 'Gabriela Fuentes', 'email' => 'gabriela.fuentes200@example.com', 'role' => 'student'],
    ['name' => 'Valeria Silva', 'email' => 'valeria.silva201@example.com', 'role' => 'student'],
    ['name' => 'Noelia Soto', 'email' => 'noelia.soto202@example.com', 'role' => 'student'],
    ['name' => 'Patricia Aguilar', 'email' => 'patricia.aguilar203@example.com', 'role' => 'student'],
    ['name' => 'Paola Soto', 'email' => 'paola.soto204@example.com', 'role' => 'student'],
    ['name' => 'Valentina Navarro', 'email' => 'valentina.navarro205@example.com', 'role' => 'student'],
    ['name' => 'Valentina Cordero', 'email' => 'valentina.cordero206@example.com', 'role' => 'student'],
    ['name' => 'Sofía Ruiz', 'email' => 'sofía.ruiz207@example.com', 'role' => 'student'],
    ['name' => 'Eva García', 'email' => 'eva.garcía208@example.com', 'role' => 'student'],
    ['name' => 'Diego Duarte', 'email' => 'diego.duarte209@example.com', 'role' => 'student'],
    ['name' => 'Patricia Rodríguez', 'email' => 'patricia.rodríguez210@example.com', 'role' => 'student'],
    ['name' => 'Noelia Ramos', 'email' => 'noelia.ramos211@example.com', 'role' => 'student'],
    ['name' => 'Paula González', 'email' => 'paula.gonzález212@example.com', 'role' => 'student'],
    ['name' => 'Manuel Salas', 'email' => 'manuel.salas213@example.com', 'role' => 'student'],
    ['name' => 'Raúl Rodríguez', 'email' => 'raúl.rodríguez214@example.com', 'role' => 'student'],
    ['name' => 'Diego Ramos', 'email' => 'diego.ramos215@example.com', 'role' => 'student'],
    ['name' => 'Diego Pineda', 'email' => 'diego.pineda216@example.com', 'role' => 'student'],
    ['name' => 'Diego Pineda', 'email' => 'diego.pineda217@example.com', 'role' => 'student'],
    ['name' => 'Matías Navarro', 'email' => 'matías.navarro218@example.com', 'role' => 'student'],
    ['name' => 'Andrea García', 'email' => 'andrea.garcía219@example.com', 'role' => 'student'],
    ['name' => 'Valeria Mejía', 'email' => 'valeria.mejía220@example.com', 'role' => 'student'],
    ['name' => 'Kevin Castillo', 'email' => 'kevin.castillo221@example.com', 'role' => 'student'],
    ['name' => 'Brenda Hernández', 'email' => 'brenda.hernández222@example.com', 'role' => 'student'],
    ['name' => 'Elena Lozano', 'email' => 'elena.lozano223@example.com', 'role' => 'student'],
    ['name' => 'Héctor Chávez', 'email' => 'héctor.chávez224@example.com', 'role' => 'student'],
    ['name' => 'Valentina Rodríguez', 'email' => 'valentina.rodríguez225@example.com', 'role' => 'student'],
    ['name' => 'Sara León', 'email' => 'sara.león226@example.com', 'role' => 'student'],
    ['name' => 'Julieta Mejía', 'email' => 'julieta.mejía227@example.com', 'role' => 'student'],
    ['name' => 'Raúl Vega', 'email' => 'raúl.vega228@example.com', 'role' => 'student'],
    ['name' => 'Gabriela Ramírez', 'email' => 'gabriela.ramírez229@example.com', 'role' => 'student'],
    ['name' => 'Héctor Rodríguez', 'email' => 'héctor.rodríguez230@example.com', 'role' => 'student'],
    ['name' => 'Elena Ríos', 'email' => 'elena.ríos231@example.com', 'role' => 'student'],
    ['name' => 'Brenda Cortés', 'email' => 'brenda.cortés232@example.com', 'role' => 'student'],
    ['name' => 'Lucía León', 'email' => 'lucía.león233@example.com', 'role' => 'student'],
    ['name' => 'Fernanda Paredes', 'email' => 'fernanda.paredes234@example.com', 'role' => 'student'],
    ['name' => 'Kevin Lozano', 'email' => 'kevin.lozano235@example.com', 'role' => 'student'],
    ['name' => 'Ana Aguilar', 'email' => 'ana.aguilar236@example.com', 'role' => 'student'],
    ['name' => 'Emilio Fuentes', 'email' => 'emilio.fuentes237@example.com', 'role' => 'student'],
    ['name' => 'Natalia Soto', 'email' => 'natalia.soto238@example.com', 'role' => 'student'],
    ['name' => 'Ricardo Romero', 'email' => 'ricardo.romero239@example.com', 'role' => 'student'],
    ['name' => 'Roberto Miranda', 'email' => 'roberto.miranda240@example.com', 'role' => 'student'],
    ['name' => 'Manuel Duarte', 'email' => 'manuel.duarte241@example.com', 'role' => 'student'],
    ['name' => 'Ana Salas', 'email' => 'ana.salas242@example.com', 'role' => 'student'],
    ['name' => 'Sofía Castillo', 'email' => 'sofía.castillo243@example.com', 'role' => 'student'],
    ['name' => 'Julia Silva', 'email' => 'julia.silva244@example.com', 'role' => 'student'],
    ['name' => 'Claudia Salinas', 'email' => 'claudia.salinas245@example.com', 'role' => 'student'],
    ['name' => 'Gabriel Fuentes', 'email' => 'gabriel.fuentes246@example.com', 'role' => 'student'],
    ['name' => 'Clara Martínez', 'email' => 'clara.martínez247@example.com', 'role' => 'student'],
    ['name' => 'Iván Salas', 'email' => 'iván.salas248@example.com', 'role' => 'student'],
    ['name' => 'Andrea Paz', 'email' => 'andrea.paz249@example.com', 'role' => 'student'],
    ['name' => 'Regina Navarro', 'email' => 'regina.navarro250@example.com', 'role' => 'student'],
    ['name' => 'Noelia Duarte', 'email' => 'noelia.duarte251@example.com', 'role' => 'student'],
    ['name' => 'Rocío Campos', 'email' => 'rocío.campos252@example.com', 'role' => 'student'],
    ['name' => 'Claudia Ramírez', 'email' => 'claudia.ramírez253@example.com', 'role' => 'student'],
    ['name' => 'Brenda Méndez', 'email' => 'brenda.méndez254@example.com', 'role' => 'student'],
    ['name' => 'Noelia González', 'email' => 'noelia.gonzález255@example.com', 'role' => 'student'],
    ['name' => 'Regina Paz', 'email' => 'regina.paz256@example.com', 'role' => 'student'],
    ['name' => 'Cristian Salas', 'email' => 'cristian.salas257@example.com', 'role' => 'student'],
    ['name' => 'Ángel Vega', 'email' => 'ángel.vega258@example.com', 'role' => 'student'],
    ['name' => 'Rocío Salinas', 'email' => 'rocío.salinas259@example.com', 'role' => 'student'],
    ['name' => 'Sebastián Paz', 'email' => 'sebastián.paz260@example.com', 'role' => 'student'],
    ['name' => 'Marco Ponce', 'email' => 'marco.ponce261@example.com', 'role' => 'student'],
    ['name' => 'Ricardo Silva', 'email' => 'ricardo.silva262@example.com', 'role' => 'student'],
    ['name' => 'Iván Lozano', 'email' => 'iván.lozano263@example.com', 'role' => 'student'],
    ['name' => 'Andrés Navarro', 'email' => 'andrés.navarro264@example.com', 'role' => 'student'],
    ['name' => 'Florencia Acosta', 'email' => 'florencia.acosta265@example.com', 'role' => 'student'],
    ['name' => 'Patricia Ortega', 'email' => 'patricia.ortega266@example.com', 'role' => 'student'],
    ['name' => 'Tomás Salinas', 'email' => 'tomás.salinas267@example.com', 'role' => 'student'],
    ['name' => 'Luis Miranda', 'email' => 'luis.miranda268@example.com', 'role' => 'student'],
    ['name' => 'Isabel Salas', 'email' => 'isabel.salas269@example.com', 'role' => 'student'],
    ['name' => 'Manuel Castillo', 'email' => 'manuel.castillo270@example.com', 'role' => 'student'],
    ['name' => 'Paula Chávez', 'email' => 'paula.chávez271@example.com', 'role' => 'student'],
    ['name' => 'David Rodríguez', 'email' => 'david.rodríguez272@example.com', 'role' => 'student'],
    ['name' => 'Lucía Soto', 'email' => 'lucía.soto273@example.com', 'role' => 'student'],
    ['name' => 'Kevin Molina', 'email' => 'kevin.molina274@example.com', 'role' => 'student'],
    ['name' => 'Ximena Rodríguez', 'email' => 'ximena.rodríguez275@example.com', 'role' => 'student'],
    ['name' => 'Alonso Cabrera', 'email' => 'alonso.cabrera276@example.com', 'role' => 'student'],
    ['name' => 'Roberto Ortiz', 'email' => 'roberto.ortiz277@example.com', 'role' => 'student'],
    ['name' => 'Valentina Gómez', 'email' => 'valentina.gómez278@example.com', 'role' => 'student'],
    ['name' => 'Eva Cabrera', 'email' => 'eva.cabrera279@example.com', 'role' => 'student'],
    ['name' => 'Patricia Cabrera', 'email' => 'patricia.cabrera280@example.com', 'role' => 'student'],
    ['name' => 'Valeria Salinas', 'email' => 'valeria.salinas281@example.com', 'role' => 'student'],
    ['name' => 'Raúl Molina', 'email' => 'raúl.molina282@example.com', 'role' => 'student'],
    ['name' => 'Isabella Varela', 'email' => 'isabella.varela283@example.com', 'role' => 'student'],
    ['name' => 'Felipe León', 'email' => 'felipe.león284@example.com', 'role' => 'student'],
    ['name' => 'Carmen Méndez', 'email' => 'carmen.méndez285@example.com', 'role' => 'student'],
    ['name' => 'Paola Gómez', 'email' => 'paola.gómez286@example.com', 'role' => 'student'],
    ['name' => 'Renata Ramírez', 'email' => 'renata.ramírez287@example.com', 'role' => 'student'],
    ['name' => 'Samuel Cabrera', 'email' => 'samuel.cabrera288@example.com', 'role' => 'student'],
    ['name' => 'Ángel Molina', 'email' => 'ángel.molina289@example.com', 'role' => 'student'],
    ['name' => 'Natalia Campos', 'email' => 'natalia.campos290@example.com', 'role' => 'student'],
    ['name' => 'Jessica Castro', 'email' => 'jessica.castro291@example.com', 'role' => 'student'],
    ['name' => 'Fernanda Cabrera', 'email' => 'fernanda.cabrera292@example.com', 'role' => 'student'],
    ['name' => 'Sebastián Ortega', 'email' => 'sebastián.ortega293@example.com', 'role' => 'student'],
    ['name' => 'Felipe Soto', 'email' => 'felipe.soto294@example.com', 'role' => 'student'],
    ['name' => 'Tomás Mendoza', 'email' => 'tomás.mendoza295@example.com', 'role' => 'student'],
    ['name' => 'Manuel Ríos', 'email' => 'manuel.ríos296@example.com', 'role' => 'student'],
    ['name' => 'Jessica Duarte', 'email' => 'jessica.duarte297@example.com', 'role' => 'student'],
    ['name' => 'Cristian Lozano', 'email' => 'cristian.lozano298@example.com', 'role' => 'student'],
    ['name' => 'Lucía Ortega', 'email' => 'lucía.ortega299@example.com', 'role' => 'student'],
];
User::insert($students);

//seeder profesores
$professors = [
    ['name' => 'Elena Díaz', 'email' => 'elena.díaz0@example.com', 'role' => 'professor'],
    ['name' => 'Lucía Antonio', 'email' => 'lucía.antonio1@example.com', 'role' => 'professor'],
    ['name' => 'Natalia Cabrera', 'email' => 'natalia.cabrera2@example.com', 'role' => 'professor'],
    ['name' => 'Melisa Ortiz', 'email' => 'melisa.ortiz3@example.com', 'role' => 'professor'],
    ['name' => 'Emiliano Paredes', 'email' => 'emiliano.paredes4@example.com', 'role' => 'professor'],
    ['name' => 'Eva Cordero', 'email' => 'eva.cordero5@example.com', 'role' => 'professor'],
    ['name' => 'Tomás Herrera', 'email' => 'tomás.herrera6@example.com', 'role' => 'professor'],
    ['name' => 'Samuel Morales', 'email' => 'samuel.morales7@example.com', 'role' => 'professor'],
    ['name' => 'Florencia Peña', 'email' => 'florencia.peña8@example.com', 'role' => 'professor'],
    ['name' => 'Cristian Cordero', 'email' => 'cristian.cordero9@example.com', 'role' => 'professor'],
    ['name' => 'Cristian Soto', 'email' => 'cristian.soto10@example.com', 'role' => 'professor'],
    ['name' => 'Sara Paz', 'email' => 'sara.paz11@example.com', 'role' => 'professor'],
    ['name' => 'Daniela Castro', 'email' => 'daniela.castro12@example.com', 'role' => 'professor'],
    ['name' => 'Melisa Cordero', 'email' => 'melisa.cordero13@example.com', 'role' => 'professor'],
    ['name' => 'Rocío Pinto', 'email' => 'rocío.pinto14@example.com', 'role' => 'professor'],
    ['name' => 'Paula Ríos', 'email' => 'paula.ríos15@example.com', 'role' => 'professor'],
    ['name' => 'Ricardo López', 'email' => 'ricardo.lópez16@example.com', 'role' => 'professor'],
    ['name' => 'Brenda Palma', 'email' => 'brenda.palma17@example.com', 'role' => 'professor'],
    ['name' => 'Jessica Peña', 'email' => 'jessica.peña18@example.com', 'role' => 'professor'],
    ['name' => 'Mónica Navarro', 'email' => 'mónica.navarro19@example.com', 'role' => 'professor'],
    ['name' => 'Kevin Martínez', 'email' => 'kevin.martínez20@example.com', 'role' => 'professor'],
    ['name' => 'Claudia Villanueva', 'email' => 'claudia.villanueva21@example.com', 'role' => 'professor'],
    ['name' => 'Pedro Salas', 'email' => 'pedro.salas22@example.com', 'role' => 'professor'],
    ['name' => 'Daniela Hernández', 'email' => 'daniela.hernández23@example.com', 'role' => 'professor'],
    ['name' => 'Melisa Pinto', 'email' => 'melisa.pinto24@example.com', 'role' => 'professor'],
    ['name' => 'Manuel Vargas', 'email' => 'manuel.vargas25@example.com', 'role' => 'professor'],
    ['name' => 'Regina Luna', 'email' => 'regina.luna26@example.com', 'role' => 'professor'],
    ['name' => 'Fernanda Lozano', 'email' => 'fernanda.lozano27@example.com', 'role' => 'professor'],
    ['name' => 'Florencia Paredes', 'email' => 'florencia.paredes28@example.com', 'role' => 'professor'],
    ['name' => 'Elena Díaz', 'email' => 'elena.díaz29@example.com', 'role' => 'professor'],
    ['name' => 'José Luna', 'email' => 'josé.luna30@example.com', 'role' => 'professor'],
    ['name' => 'Ana Méndez', 'email' => 'ana.méndez31@example.com', 'role' => 'professor'],
    ['name' => 'Javier Vega', 'email' => 'javier.vega32@example.com', 'role' => 'professor'],
    ['name' => 'Oscar Ortiz', 'email' => 'oscar.ortiz33@example.com', 'role' => 'professor'],
    ['name' => 'Felipe Acosta', 'email' => 'felipe.acosta34@example.com', 'role' => 'professor'],
    ['name' => 'Alejandra Peña', 'email' => 'alejandra.peña35@example.com', 'role' => 'professor'],
    ['name' => 'Florencia Castro', 'email' => 'florencia.castro36@example.com', 'role' => 'professor'],
    ['name' => 'Sebastián Ponce', 'email' => 'sebastián.ponce37@example.com', 'role' => 'professor'],
    ['name' => 'Laura Fuentes', 'email' => 'laura.fuentes38@example.com', 'role' => 'professor'],
    ['name' => 'Valeria Navarro', 'email' => 'valeria.navarro39@example.com', 'role' => 'professor'],
    ['name' => 'Constanza Torres', 'email' => 'constanza.torres40@example.com', 'role' => 'professor'],
    ['name' => 'Regina Guzmán', 'email' => 'regina.guzmán41@example.com', 'role' => 'professor'],
    ['name' => 'Matías Ortiz', 'email' => 'matías.ortiz42@example.com', 'role' => 'professor'],
    ['name' => 'Felipe Aguilar', 'email' => 'felipe.aguilar43@example.com', 'role' => 'professor'],
    ['name' => 'Julia León', 'email' => 'julia.león44@example.com', 'role' => 'professor'],
    ['name' => 'Sebastián Carrillo', 'email' => 'sebastián.carrillo45@example.com', 'role' => 'professor'],
    ['name' => 'Carmen Cortés', 'email' => 'carmen.cortés46@example.com', 'role' => 'professor'],
    ['name' => 'Marina Vega', 'email' => 'marina.vega47@example.com', 'role' => 'professor'],
    ['name' => 'Valentina Jiménez', 'email' => 'valentina.jiménez48@example.com', 'role' => 'professor'],
    ['name' => 'Samuel Ortega', 'email' => 'samuel.ortega49@example.com', 'role' => 'professor'],
    ['name' => 'Javier Carrillo', 'email' => 'javier.carrillo50@example.com', 'role' => 'professor'],
    ['name' => 'Camila Castro', 'email' => 'camila.castro51@example.com', 'role' => 'professor'],
    ['name' => 'Renata Romero', 'email' => 'renata.romero52@example.com', 'role' => 'professor'],
    ['name' => 'Renata Moreno', 'email' => 'renata.moreno53@example.com', 'role' => 'professor'],
    ['name' => 'Héctor Vargas', 'email' => 'héctor.vargas54@example.com', 'role' => 'professor'],
    ['name' => 'Kevin Moreno', 'email' => 'kevin.moreno55@example.com', 'role' => 'professor'],
    ['name' => 'Claudia Palma', 'email' => 'claudia.palma56@example.com', 'role' => 'professor'],
    ['name' => 'Andrés Lozano', 'email' => 'andrés.lozano57@example.com', 'role' => 'professor'],
    ['name' => 'David Méndez', 'email' => 'david.méndez58@example.com', 'role' => 'professor'],
    ['name' => 'Melisa Lozano', 'email' => 'melisa.lozano59@example.com', 'role' => 'professor'],
    ['name' => 'Patricia Aguilar', 'email' => 'patricia.aguilar60@example.com', 'role' => 'professor'],
    ['name' => 'Eva Cabrera', 'email' => 'eva.cabrera61@example.com', 'role' => 'professor'],
    ['name' => 'Diego Vargas', 'email' => 'diego.vargas62@example.com', 'role' => 'professor'],
    ['name' => 'Florencia Soto', 'email' => 'florencia.soto63@example.com', 'role' => 'professor'],
    ['name' => 'Mónica Cabrera', 'email' => 'mónica.cabrera64@example.com', 'role' => 'professor'],
    ['name' => 'Julia Ortiz', 'email' => 'julia.ortiz65@example.com', 'role' => 'professor'],
    ['name' => 'Alejandra Ramírez', 'email' => 'alejandra.ramírez66@example.com', 'role' => 'professor'],
    ['name' => 'Andrés Ponce', 'email' => 'andrés.ponce67@example.com', 'role' => 'professor'],
    ['name' => 'Sebastián Paz', 'email' => 'sebastián.paz68@example.com', 'role' => 'professor'],
    ['name' => 'Natalia Ortiz', 'email' => 'natalia.ortiz69@example.com', 'role' => 'professor'],
    ['name' => 'Héctor Rivas', 'email' => 'héctor.rivas70@example.com', 'role' => 'professor'],
    ['name' => 'Raúl Díaz', 'email' => 'raúl.díaz71@example.com', 'role' => 'professor'],
    ['name' => 'Tomás Moreno', 'email' => 'tomás.moreno72@example.com', 'role' => 'professor'],
    ['name' => 'Alejandra Castro', 'email' => 'alejandra.castro73@example.com', 'role' => 'professor'],
    ['name' => 'Matías Rodríguez', 'email' => 'matías.rodríguez74@example.com', 'role' => 'professor'],
    ['name' => 'Felipe Méndez', 'email' => 'felipe.méndez75@example.com', 'role' => 'professor'],
    ['name' => 'Alonso Ponce', 'email' => 'alonso.ponce76@example.com', 'role' => 'professor'],
    ['name' => 'Marco Cordero', 'email' => 'marco.cordero77@example.com', 'role' => 'professor'],
    ['name' => 'Paola Molina', 'email' => 'paola.molina78@example.com', 'role' => 'professor'],
    ['name' => 'Andrés Palma', 'email' => 'andrés.palma79@example.com', 'role' => 'professor'],
    ['name' => 'Samuel Acosta', 'email' => 'samuel.acosta80@example.com', 'role' => 'professor'],
    ['name' => 'Alejandra Salas', 'email' => 'alejandra.salas81@example.com', 'role' => 'professor'],
    ['name' => 'Martín Antonio', 'email' => 'martín.antonio82@example.com', 'role' => 'professor'],
    ['name' => 'Valeria Pinto', 'email' => 'valeria.pinto83@example.com', 'role' => 'professor'],
    ['name' => 'Oscar Navarro', 'email' => 'oscar.navarro84@example.com', 'role' => 'professor'],
    ['name' => 'Natalia Ramírez', 'email' => 'natalia.ramírez85@example.com', 'role' => 'professor'],
    ['name' => 'Noelia Pinto', 'email' => 'noelia.pinto86@example.com', 'role' => 'professor'],
    ['name' => 'Regina Soto', 'email' => 'regina.soto87@example.com', 'role' => 'professor'],
    ['name' => 'Gabriel Peña', 'email' => 'gabriel.peña88@example.com', 'role' => 'professor'],
    ['name' => 'Rocío Hernández', 'email' => 'rocío.hernández89@example.com', 'role' => 'professor'],
    ['name' => 'Diana Campos', 'email' => 'diana.campos90@example.com', 'role' => 'professor'],
    ['name' => 'Tamara Lozano', 'email' => 'tamara.lozano91@example.com', 'role' => 'professor'],
    ['name' => 'Isabella Ramírez', 'email' => 'isabella.ramírez92@example.com', 'role' => 'professor'],
    ['name' => 'Ángel Díaz', 'email' => 'ángel.díaz93@example.com', 'role' => 'professor'],
    ['name' => 'Valeria Ramírez', 'email' => 'valeria.ramírez94@example.com', 'role' => 'professor'],
    ['name' => 'Tamara Cortés', 'email' => 'tamara.cortés95@example.com', 'role' => 'professor'],
    ['name' => 'Daniela Gómez', 'email' => 'daniela.gómez96@example.com', 'role' => 'professor'],
    ['name' => 'Cristian Cortés', 'email' => 'cristian.cortés97@example.com', 'role' => 'professor'],
    ['name' => 'Paula Pérez', 'email' => 'paula.pérez98@example.com', 'role' => 'professor'],
    ['name' => 'Oscar Méndez', 'email' => 'oscar.méndez99@example.com', 'role' => 'professor'],
    ['name' => 'Pedro Castro', 'email' => 'pedro.castro100@example.com', 'role' => 'professor'],
    ['name' => 'Regina Jiménez', 'email' => 'regina.jiménez101@example.com', 'role' => 'professor'],
    ['name' => 'Valeria García', 'email' => 'valeria.garcía102@example.com', 'role' => 'professor'],
    ['name' => 'Raúl Carrillo', 'email' => 'raúl.carrillo103@example.com', 'role' => 'professor'],
    ['name' => 'Camila Cordero', 'email' => 'camila.cordero104@example.com', 'role' => 'professor'],
    ['name' => 'Isabella Varela', 'email' => 'isabella.varela105@example.com', 'role' => 'professor'],
    ['name' => 'Elena Soto', 'email' => 'elena.soto106@example.com', 'role' => 'professor'],
    ['name' => 'Claudia Lozano', 'email' => 'claudia.lozano107@example.com', 'role' => 'professor'],
    ['name' => 'Regina Miranda', 'email' => 'regina.miranda108@example.com', 'role' => 'professor'],
    ['name' => 'Valentina Mendoza', 'email' => 'valentina.mendoza109@example.com', 'role' => 'professor'],
    ['name' => 'Clara Miranda', 'email' => 'clara.miranda110@example.com', 'role' => 'professor'],
    ['name' => 'Carmen Mendoza', 'email' => 'carmen.mendoza111@example.com', 'role' => 'professor'],
    ['name' => 'Samuel Rivera', 'email' => 'samuel.rivera112@example.com', 'role' => 'professor'],
    ['name' => 'Cristian Molina', 'email' => 'cristian.molina113@example.com', 'role' => 'professor'],
    ['name' => 'Emiliano Mendoza', 'email' => 'emiliano.mendoza114@example.com', 'role' => 'professor'],
    ['name' => 'Javier Soto', 'email' => 'javier.soto115@example.com', 'role' => 'professor'],
    ['name' => 'Carmen Romero', 'email' => 'carmen.romero116@example.com', 'role' => 'professor'],
    ['name' => 'Roberto Lozano', 'email' => 'roberto.lozano117@example.com', 'role' => 'professor'],
    ['name' => 'Manuel Hernández', 'email' => 'manuel.hernández118@example.com', 'role' => 'professor'],
    ['name' => 'Ximena Pineda', 'email' => 'ximena.pineda119@example.com', 'role' => 'professor'],
    ['name' => 'Ángel Salgado', 'email' => 'ángel.salgado120@example.com', 'role' => 'professor'],
    ['name' => 'Natalia Chávez', 'email' => 'natalia.chávez121@example.com', 'role' => 'professor'],
    ['name' => 'Diana Luna', 'email' => 'diana.luna122@example.com', 'role' => 'professor'],
    ['name' => 'Diana Vega', 'email' => 'diana.vega123@example.com', 'role' => 'professor'],
    ['name' => 'Roberto Ríos', 'email' => 'roberto.ríos124@example.com', 'role' => 'professor'],
    ['name' => 'Constanza Rivas', 'email' => 'constanza.rivas125@example.com', 'role' => 'professor'],
    ['name' => 'Sebastián Peña', 'email' => 'sebastián.peña126@example.com', 'role' => 'professor'],
    ['name' => 'Julieta Palma', 'email' => 'julieta.palma127@example.com', 'role' => 'professor'],
    ['name' => 'Rocío López', 'email' => 'rocío.lópez128@example.com', 'role' => 'professor'],
    ['name' => 'Manuel Vega', 'email' => 'manuel.vega129@example.com', 'role' => 'professor'],
    ['name' => 'Eva Pinto', 'email' => 'eva.pinto130@example.com', 'role' => 'professor'],
    ['name' => 'Isabella Reyes', 'email' => 'isabella.reyes131@example.com', 'role' => 'professor'],
    ['name' => 'Alejandra Reyes', 'email' => 'alejandra.reyes132@example.com', 'role' => 'professor'],
    ['name' => 'Sebastián Silva', 'email' => 'sebastián.silva133@example.com', 'role' => 'professor'],
    ['name' => 'Sara Duarte', 'email' => 'sara.duarte134@example.com', 'role' => 'professor'],
    ['name' => 'Sebastián Vargas', 'email' => 'sebastián.vargas135@example.com', 'role' => 'professor'],
    ['name' => 'Matías Castro', 'email' => 'matías.castro136@example.com', 'role' => 'professor'],
    ['name' => 'Renata Castillo', 'email' => 'renata.castillo137@example.com', 'role' => 'professor'],
    ['name' => 'Carmen Méndez', 'email' => 'carmen.méndez138@example.com', 'role' => 'professor'],
    ['name' => 'Valeria Silva', 'email' => 'valeria.silva139@example.com', 'role' => 'professor'],
    ['name' => 'Sebastián Vega', 'email' => 'sebastián.vega140@example.com', 'role' => 'professor'],
    ['name' => 'Emiliano Pérez', 'email' => 'emiliano.pérez141@example.com', 'role' => 'professor'],
    ['name' => 'Esteban Silva', 'email' => 'esteban.silva142@example.com', 'role' => 'professor'],
    ['name' => 'Martín Rodríguez', 'email' => 'martín.rodríguez143@example.com', 'role' => 'professor'],
    ['name' => 'Emiliano Vargas', 'email' => 'emiliano.vargas144@example.com', 'role' => 'professor'],
    ['name' => 'Gabriel León', 'email' => 'gabriel.león145@example.com', 'role' => 'professor'],
    ['name' => 'Alonso Paredes', 'email' => 'alonso.paredes146@example.com', 'role' => 'professor'],
    ['name' => 'Claudia Soto', 'email' => 'claudia.soto147@example.com', 'role' => 'professor'],
    ['name' => 'Natalia Martínez', 'email' => 'natalia.martínez148@example.com', 'role' => 'professor'],
    ['name' => 'Valeria Soto', 'email' => 'valeria.soto149@example.com', 'role' => 'professor'],
];

User::insert($professors);

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
            'status' =>1,
            'school_id' => $school->id,
        ]);
    }
}
$letras = range('a', 'z');        // Genera de 'a' a 'z'
$numeros = range(0, 9);           // Genera del 0 al 9
$alfanumerico = array_merge($letras, $numeros);
$courses = Course::select("id")->get();
foreach ($courses as $course) {
    $cantidadSecciones = rand(1, 6);

    for ($i = 0; $i < $cantidadSecciones; $i++) {
        $codigo = implode('', array_map(
            fn($i) => $alfanumerico[array_rand($alfanumerico)],
            range(1, 6) // 6 caracteres aleatorios
        ));

        $professor = User::where('role', 'professor')->inRandomOrder()->first();

        Section::create([
            'course_id' => $course->id,
            'code' => strtoupper($codigo),
            'user_id' => $professor->id, // Asegúrate que la columna exista
        ]);
    }
}


//seeder Matricula
    $studentIds = User::where('role', 'student')->pluck('id')->toArray();
     $courseIds = Section::pluck('id')->toArray();

    foreach ($studentIds as $studentId) {
        $selectedCourses = collect($courseIds)->random(rand(1, 6));
        foreach ($selectedCourses as $courseId) {
            Enrollment::create([
                'section_id' => $courseId,
                'user_id' => $studentId,
            ]);
            }
        }
 Survey::create([
            'revision' => 'Evaluacion #2 Mayo 2026',
            'dateStart' => '2025-10-12',
            'dateEnd' => '2025-11-12',
            'Author' => 'admin1',
            'term' => '1',
            'status' =>1,
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
