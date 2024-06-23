<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopicObjects extends Model
{
    use HasFactory;

    protected $fillable = ['topic_id', 'type', 'content', 'file_path', 'test_id'];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function test()
    {
        return $this->belongsTo(Tests::class, 'test_id');
    }
}

