<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
      protected $fillable = [
        'sigaId',
        'DESCRIPCION_ESCUELA',
        'director_id',
    ];

  public function courses()
  {
    return $this->hasMany(Course::class);
  }
}
