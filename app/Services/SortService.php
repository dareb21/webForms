<?php
namespace App\Services;
class SortService
{
    public function sortSchools($schoolsArray)
    {
        //Aqui si llego con un return
    
     $schoolsSorted = [];
     foreach ($schoolsArray as $school)
        {
            
        $schoolsSorted[]=[
            "id"=> $school["ID_ESCUELA"],
            "name"=> $school["DESCRIPCION_ESCUELA"],
            "director_id"=> $school["ID_USUARIO"],
        ];  
    }
    
    return $schoolsSorted;
    }
     
     

    public function sortTermClasses($classesArray)
    {
        $validateProfessor = [];
        $validateCourse = [];
        $validateSection = [];

        $professors=[];
        $courses=[];
        $sections=[];

        foreach ($classesArray as $class)
            {
                return $class;
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
                return $professors;
            }
        

    }
}
