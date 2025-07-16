<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
  use HasFactory;
  protected $fillable=[
        "name",
        "status",
  ];

 

public function thisSchool()
{
  return $this->belongsTo(School::class,"school_id");
}

public function sections()
{
  return $this->hasMany(Section::class);
}

}
