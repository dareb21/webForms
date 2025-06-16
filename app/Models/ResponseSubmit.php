<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ResponseSubmit extends Model
{
  
  use HasFactory;
    protected $fillable = [
        'submitId',
        'optionId',
    ];
}
