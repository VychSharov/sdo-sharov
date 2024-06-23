<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\MyCourses;

class CourseEnrollmentController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function enroll($id)
    {
        $user = Auth::user();

        $enrollment = MyCourses::firstOrCreate(
            ['user_id' => $user->id, 'course_id' => $id],
            ['status' => 'enrolled']
        );

        return response()->json(['success' => true]);
    }
}
