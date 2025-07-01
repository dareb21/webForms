<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
      protected $fillable = [
        'name',
        'director_id',
    ];


    
public function user()
  {
    return $this->belongsTo(User::class, 'director_id');
  }
}
