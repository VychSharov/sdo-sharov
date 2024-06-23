<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courses extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'instructor',
        'thumbnail',
        'current_price',
        'course_overview',
    ];

    public function episodes()
    {
        return $this->hasMany(CourseEpisodes::class);
    }
}

