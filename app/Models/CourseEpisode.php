<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseEpisode extends Model
{
    use HasFactory;

    protected $table = 'course_episodes';

    protected $fillable = [
        'course_id',
        'episode_title',
        'episode_description',
        'episode_thumbnail',
        'episode_video',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function topics()
    {
        return $this->hasMany(Topic::class, 'course_episodes_id');
    }


}
