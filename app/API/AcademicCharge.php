<?php
namespace App\API;
use Illuminate\Support\Facades\Http;
use App\Services\SortService;
use App\Services\ValidatedTerm;
use App\Services\ApiToken;
use Illuminate\Support\Facades\DB;
use App\Models\School;
use App\Models\Course;
use App\Models\Section;
use App\Models\User;
use App\Models\Term;

class AcademicCharge
{
    private $token;
    public function __construct(private SortService $sortService, private validatedTerm $validatedTerm, private ApiToken $apiToken)
    {
        $this->token = $this->apiToken->getToken();
    }

    public function getSchoolData()
    {
        $schoolApiResponse = Http::withToken($this->token)->get("https://melioris.usap.edu/api/evaldoc/v1/escuelas")->json();
      return $this->sortService->sortSchools($schoolApiResponse);                
    }

    public function getTermClassesData($termInfo)
    {
        $termClassesApiResponse = Http::withToken($this->token)->get("https://melioris.usap.edu/api/evaldoc/v1/periodo-academico/".$termInfo["newTerm"]."/oferta-academica")->json();
        return  $this->sortService->sortTermClasses($termClassesApiResponse,$termInfo["newTermId"]);
         
    }

    public function getAuthorities()
    {   
     $authoritiesApiResponse= Http::withToken($this->token)->get("https://melioris.usap.edu/api/evaldoc/v1/autoridades")->json();
    return $this->sortService->sortAuthorities($authoritiesApiResponse);
    }

    public function validatedTerm()
    {
    $thisTermAPI = Http::withToken($this->token)->get('https://melioris.usap.edu/api/evaldoc/v1/periodo-actual')->json();
  $thisTerm = $thisTermAPI[0]["periodo-actual"];
  return $this->validatedTerm->validatedTerm($thisTerm);
    }

    public function updateCharge($authorities,$schoolsData,$termClassesData,$newTerm)
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
           Term::where("id",$newTerm)->delete();
           throw new \Exception("Error updating academic charge: " . $e->getMessage());
                            DB::rollBack();
                return false;
        }
        return true;
    }


}
