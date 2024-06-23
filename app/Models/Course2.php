<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course2 extends Model
{
    use HasFactory;

     protected $table = 'courses'; // Убедитесь, что имя таблицы указано правильно

    public function myCourses()
    {
        return $this->hasMany(MyCourse2::class);
    }
}

