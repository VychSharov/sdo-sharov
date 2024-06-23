<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Topic;
use App\Models\TopicObjects;
use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class MyCoursesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function search(Request $request)
    {
        $searchTerm = $request->input('query');
        $courses = Course::where('title', 'like', '%' . $searchTerm . '%')
            ->orWhere('overview', 'like', '%' . $searchTerm . '%')
            ->orderBy('id', 'DESC')
            ->get();
        return response()->json($courses);
    }

     public function index(Request $request)
    {
        // Получаем ID текущего пользователя
        $userId = $request->user()->id;

        // Извлекаем курсы, на которые записан пользователь
        $courses = Course::whereHas('myCourses', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->orderBy('id', 'DESC')->get();

        // Возвращаем их на страницу
        return Inertia::render('MyCourses', [
            'courses' => $courses
        ]);
    }
}
