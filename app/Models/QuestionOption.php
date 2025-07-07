<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class QuestionOption extends Model
{
   
  use HasFactory;
    protected $fillable = [
        'question_group_id',
        'option',
        'calification', 
    ];


    public function QuestionGroup()
    {
      return $this->belongsTo(QuestionGroup::class,"question_group_id");
    }
}
