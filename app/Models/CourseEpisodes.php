<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseEpisodes extends Model
{
    use HasFactory;

    use HasFactory;

    protected $fillable = ['course_id', 'episode_number', 'episode_title', 'episode_description', 'episode_thumbnail', 'episode_video', 'user_id'];

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }
}

