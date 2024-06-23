<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyCourse2 extends Model
{
    use HasFactory;

    protected $table = 'my_courses'; // Убедитесь, что имя таблицы указано правильно

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
