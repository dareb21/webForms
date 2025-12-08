<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class School extends Model
{
      use SoftDeletes;
      protected $fillable = [
        'id',
        'name',
        'director_id',
    ];

  public function courses()
  {
    return $this->hasMany(Course::class);
  }
}
