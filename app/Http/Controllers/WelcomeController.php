<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Courses;
use App\Models\TopicObjects;
use App\Models\CourseEpisodes;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function index()
    {
        $courses = Courses::with('user')->get();
        return inertia('Welcome', ['courses' => $courses]);
    }

}
