<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes;

  protected $fillable=[
        "name",
        "school_id",
        "term_id"
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
