<?php
namespace App\Services;
class SortService
{
    public function sortSchools($schoolsArray)
    {
     $schoolsSorted = [];
     foreach ($schoolsArray as $school)
        {
            
        $schoolsSorted[]=[
            "id"=> $school["ID_ESCUELA"],
            "name"=> $school["DESCRIPCION_ESCUELA"],
            "director_id"=> $school["ID_USUARIO"] ? $school["ID_USUARIO"] : 88,
        ];  
    }
    
    return $schoolsSorted;
    }
     
     

    public function sortTermClasses($classesArray,$newTermId)
    {
        $validateProfessor = [];
        $validateCourse = [];
        $validateSection = [];

        $professors=[];
        $courses=[];
        $sections=[];

        foreach ($classesArray as $class)
            {
                
            $professorId = $class["CUENTA_CATEDRATICO"];

                if (!isset($validateProfessor[$professorId]))
                    {
                        $validateProfessor[$professorId]=true;
                        $professors[] =[
                            "id" =>$professorId,
                            "name"=>$class["NOMBRE_COMPLETO"],
                            "role"=>"Catedrático",    
                        ];  
                    }

             
            $courseId = $class["ID_CURSO"];

            if (!isset($validateCourse[$courseId]))
                {
                $validateCourse[$courseId] = true;
                $courses[]= [
                    "id"=>$courseId,
                    "name"=>$class["DESCRIPCION_CURSO"],
                    "school_id"=>$class["ID_ESCUELA"],
                    "term_id"=>$newTermId,
                ];
           }           
                
            $sectionId = $class["ID_SECCION"];
            if (!isset($validateSection[$sectionId]))
                {
                    $validateSection[$sectionId] = true;
                    $sections[] = [
                        
                        "id" =>$sectionId,
                        "course_id"=>$courseId,
                        "user_id"=> $professorId,
                        "schedule"=>$class["HORARIO_SECCION"],
                    ];
                }

             }
        
    return [
        "professors"=>$professors,
        "courses"=>$courses,
        "sections"=>$sections,
    ];

    }


    public function sortAuthorities($authoritiesArray)
        {
            $authorities = [];

            foreach ($authoritiesArray as $item)
                {
                    $authorities[] = [
                        "id" =>$item["ID_USUARIO"],
                        "name"=>$item["nombre_usuario"],
                        "email"=>$item["email"],
                        "role"=>$item["rol_usuario"],
                    ];

                }
            return $authorities;
        }
}
