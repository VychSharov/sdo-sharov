<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tests extends Model
{
    use HasFactory;

    protected $fillable = ['test_name', 'description', 'attempts', 'attempt'];

    public function questions()
    {
        return $this->hasMany(Questions::class, 'test_id');
    }

    public function objects()
    {
        return $this->hasMany(TopicObjects::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

}
