<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class SurveySubmit extends Model
{
    
  use HasFactory;
    protected $fillable = [
        'survey_id',
        'DateSubmmited',
        'user_id',
        'course_id',
        'observations',
    ];


public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

public function course()
  {
    return $this->belongsTo(Course::class, 'course_id');
  }

public function survey()
  {
    return $this->belongsTo(Survey::class, 'survey_id');
  }
}
