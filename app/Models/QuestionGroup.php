<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class QuestionGroup extends Model
{
  
  use HasFactory;
    protected $fillable = [
        'survey_id',
        'groupName',
    ];

  public function Survey()
   {
    return $this->belongsTo(Survey::class,"survey_id");
   }

   public function QuestionOption()
   {
    return $this->hasMany(QuestionOption::class,);
   }
}
