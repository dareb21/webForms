<?php
namespace App\Services;
use Illuminate\Support\Facades\Http;
use App\Services\SortService;
class AcademicCharge
{
    protected SortService $sortService;

    public function __construct(SortService $sortService)
    {
        $this->sortService = $sortService;
    }

    public function getSchoolData()
    {
        $schoolApiResponse = Http::get("https://melioris.usap.edu/api/evaldoc/v1/escuelas")->json();
      return $this->sortService->sortSchools($schoolApiResponse);                
    }

    public function getTermClassesData($thisTerm)
    {
        $termClassesApiResponse = Http::get("https://melioris.usap.edu/api/evaldoc/v1/periodo/{$thisTerm}/clases")->json();
        return  $this->sortService->sortTermClasses($termClassesApiResponse);
         
    }

}