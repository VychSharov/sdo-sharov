<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'overview', 'user_id', 'thumbnail'];

    public function episodes()
    {
        return $this->hasMany(CourseEpisode::class, 'course_id');
    }

    public function myCourses()
    {
        return $this->hasMany(MyCourse::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
