<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Survey extends Model
{
  use HasFactory;
  use SoftDeletes;
  
    protected $fillable = [
        'name',
        'dateStart',
        'dateEnd',
        'Author',
        'term',
        'status',
    ];

    public function QuestionGroup()
    {
      return $this->hasMany(QuestionGroup::class);
    }


    public static function CacheActiveSurvey() 
    {
   return  Cache::remember('ActiveSurvey', 3600, function () {
    return self::with(["QuestionGroup.QuestionOption"])->where("status",1)->first();
      });
    }
    public static function ForgetCache()
    {
      return Cache::forget("ActiveSurvey");
    }

}
