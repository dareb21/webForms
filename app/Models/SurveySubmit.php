<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class SurveySubmit extends Model
{
    
  use HasFactory;
    protected $fillable = [
        'survey_id',
        'DateSubmmited',
        'user_id',
        'section_id',
        'observations',
    ];


public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }
  
public function section()
  {
    return $this->belongsTo(Section::class, 'section_id');
  }

public function survey()
  {
    return $this->belongsTo(Survey::class, 'survey_id');
  }
}
