<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Questions extends Model
{
    use HasFactory;
    protected $table = 'question_master';
    protected $fillable = [
        'module_id',
        'quiz_id',
        'question',
        'option1',
        'option2',
        'option3',
        'option4',
        'correct_ans',
        'created_by'
    ];
}