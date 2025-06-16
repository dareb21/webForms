<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class SurveySubmit extends Model
{
    
  use HasFactory;
    protected $fillable = [
        'surveyId',
        'DateSubmmited',
        'userId',
    ];
}
