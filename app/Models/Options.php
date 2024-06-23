<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Options extends Model
{
    use HasFactory;

    protected $fillable = ['option_text', 'question_id'];

    public function correctAnswers()
    {
        return $this->hasMany(CorrectAnswers::class, 'option_id');
    }
}
