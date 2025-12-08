<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Section extends Model
{
  use SoftDeletes;
      protected $fillable = [
        'code',
        'course_id',
        'user_id',
        "status",
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
