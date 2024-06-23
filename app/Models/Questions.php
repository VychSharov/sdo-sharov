<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questions extends Model
{
    use HasFactory;

    protected $fillable = ['question_text', 'test_id'];

    public function options()
    {
        return $this->hasMany(Options::class, 'question_id');
    }
}
