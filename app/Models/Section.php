<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{

      protected $fillable = [
        'code',
        'course_id',
        'user_id'
    ];
}
