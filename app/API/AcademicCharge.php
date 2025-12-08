<?php
namespace App\API;
use Illuminate\Support\Facades\Http;
use App\Services\SortService;
use App\Services\ValidatedTerm;
use Illuminate\Support\Facades\DB;
use App\Models\School;
use App\Models\Course;
use App\Models\Section;
use App\Models\User;


class AcademicCharge
{
    protected SortService $sortService;
    protected ValidatedTerm $validatedTerm;

    public function __construct(SortService $sortService, validatedTerm $validatedTerm)
    {
        $this->sortService = $sortService;
        $this->validatedTerm = $validatedTerm;
    }

    public function getSchoolData()
    {
        $schoolApiResponse = Http::get("https://melioris.usap.edu/api/evaldoc/v1/escuelas")->json();
      return $this->sortService->sortSchools($schoolApiResponse);                
    }

    public function getTermClassesData($termInfo)
    {
        $termClassesApiResponse = Http::get("https://melioris.usap.edu/api/evaldoc/v1/periodo-academico/".$termInfo["newTerm"]."/oferta-academica")->json();
        return  $this->sortService->sortTermClasses($termClassesApiResponse,$termInfo["newTermId"]);
         
    }

    public function getAuthorities()
    {   
     $authoritiesApiResponse= Http::get("https://melioris.usap.edu/api/evaldoc/v1/autoridades")->json();
    return $this->sortService->sortAuthorities($authoritiesApiResponse);
    }

    public function validatedTerm()
    {
    $thisTermAPI = Http::get('https://melioris.usap.edu/api/evaldoc/v1/periodo-actual')->json();
  $thisTerm = $thisTermAPI[0]["periodo-actual"];
  return $this->validatedTerm->validatedTerm($thisTerm);
    }

    public function updateCharge($authorities,$schoolsData,$termClassesData)
    {
        DB::beginTransaction();
        try{
            User::query()->delete();
            School::query()->delete();
            Course::query()->delete();
            Section::query()->delete();
        
            User::insert($authorities);
            School::insert($schoolsData);
            Course::insert($termClassesData["courses"]);
            User::insert($termClassesData["professors"]);
            Section::insert($termClassesData["sections"]);

        DB::commit();
        
    }
        catch(\Exception $e)
        {
                DB::rollBack();
                return false;
        }
        return true;
    }


}
