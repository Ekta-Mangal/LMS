<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;
    protected $table = 'course_master';
    protected $fillable = [
        'title',
        'level',
        'module_count',
        'publish_date',
        'badge',
        'created_by'
    ];
}