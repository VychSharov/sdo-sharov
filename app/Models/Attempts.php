<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attempts extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'test_id', 'attempt', 'status'];

    public function evaluations()
    {
        return $this->hasMany(Evaluations::class, 'attempts_id');
    }

}
