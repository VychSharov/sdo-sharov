<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MyCourses;

class EnrollmentController extends Controller
{
    public function checkEnrollment($courseId)
    {
        $isEnrolled = MyCourses::where('user_id', Auth::id())->where('course_id', $courseId)->exists();
        return response()->json(['isEnrolled' => $isEnrolled]);
    }

    public function enroll($courseId)
    {
        $userId = Auth::id();
        $enrollment = MyCourses::firstOrCreate(
            ['user_id' => $userId, 'course_id' => $courseId],
            ['status' => 'enrolled']
        );
        return response()->json(['message' => 'Enrolled successfully']);
    }
}
