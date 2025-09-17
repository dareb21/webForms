<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{

      protected $fillable = [
        'sigaId',
        "course_id",
        "professor_id"
    ];

  public function professor()
  {
    return $this->belongsTo(User::class,'user_id');
  }
  
  public function course()
  {
    return $this->belongsTo(Course::class, "course_id");
  }

  public function submits()
  {
    return $this->hasMany(SurveySubmit::class);
  }
  }
