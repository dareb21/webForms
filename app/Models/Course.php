<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
  use HasFactory;
  protected $fillable=[
        "name",
  ];

  public function professor()
  {
    return $this->belongsTo(User::class,'user_id');
  }

  public function submits()
  {
    return $this->hasMany(SurveySubmit::class);
  }
}
