<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courses extends Model
{
    use HasFactory;

    // Укажите таблицу, если имя модели не совпадает с именем таблицы
    protected $table = 'courses';

    // Укажите заполняемые поля
    protected $fillable = [
        'title',
        'overview',
        'user_id',
        'thumbnail',
    ];

    // Определите отношения
    public function episodes()
    {
        return $this->hasMany(CourseEpisode::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function enrollments()
    {
        return $this->hasMany(MyCourse::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
