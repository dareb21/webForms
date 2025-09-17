<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Director extends Model
{
   protected $fillable = [
    'id',        
    'name',
        ];
    
    public function school()
    {
        return $this->hasOne(School::class);
    }
}
