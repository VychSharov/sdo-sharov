<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = ['topic_title', 'course_episode_id'];

    public function episode()
    {
        return $this->belongsTo(CourseEpisode::class, 'course_episodes_id');
    }

    public function objects()
    {
        return $this->hasMany(TopicObjects::class);
    }

    public function responses()
    {
        return $this->hasMany(TaskResponse::class);
    }
}
