<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\QuizsController1;
use App\Http\Controllers\QuizsController2;
use App\Http\Controllers\TestController;
use App\Http\Controllers\CourseController22;
use App\Http\Controllers\MyCoursesController;
use App\Http\Controllers\TaskResponseController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Courses;
use App\Models\User;
use App\Models\Course;

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        $courses = Course::with('user')->orderBy('id', 'DESC')->get();
        return Inertia::render('Welcome', [
            'courses' => $courses,
        ]);
    })->name('home');

    Route::get('/view-course/{id}', [CoursesController::class, 'show'])->name('course.show');
    Route::get('/watch/{id}', [CoursesController::class, 'watchepisode'])->name('course.watchepisode');
    Route::get('/quizzes', [QuizsController2::class, 'index'])->name('quizzes');
    Route::get('/quizzes/{id}', [QuizsController2::class, 'show'])->name('quizzes.show');
    Route::post('/quizzes/{id}/submit', [QuizsController2::class, 'submit'])->name('quizzes.submit');
    Route::get('/quizzes/start/{id}', [QuizsController2::class, 'startQuiz'])->name('quizzes.start');
    Route::get('/quiz/{id}', [QuizsController2::class, 'showSingleQuiz'])->name('quiz.single');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/api/tests', [TestController::class, 'index']);
    Route::get('/course-detail/{id}', [CourseController22::class, 'show'])->name('course.detail');
    Route::get('/topics/{id}', [CourseController22::class, 'showTopic'])->name('topic.show');
    Route::post('/courses/{id}/enroll', [CoursesController::class, 'enroll'])->name('courses.enroll');
    Route::delete('/courses/unenroll/{id}', [CourseController22::class, 'unenroll'])->name('courses.unenroll');
    Route::get('/my-courses', [MyCoursesController::class, 'index'])->name('my.courses');
    Route::get('/search-courses', [MyCoursesController::class, 'search'])->name('courses.search');

    Route::post('/topics/{id}/submit-response', [CourseController22::class, 'submitResponse'])->name('topics.submitResponse');
});


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/manage-students', function () {
        return Inertia::render('ManageStudents', [
            'students' => User::where('role', 'student')->OrderBy('id', 'DESC')->get()
        ]);
    })->name('manageStudents');

    Route::get('/view-student/{id}', [StudentController::class, 'show'])->name('student.show');
    Route::get('/manage-course', function () {
        return Inertia::render('ManageCourse', [
            'courses' => Courses::OrderBy('id', 'DESC')->get()
        ]);
    })->name('manageCourses');

    Route::get('/course/view/{id}', [CoursesController::class, 'viewCourse'])->name('course.view');

    Route::get('/add-course', function () {
        return Inertia::render('AddCourse');
    })->name('addCourse');

    Route::post('/addcourse', [CoursesController::class, 'store'])->name('course.store');
    Route::delete('/videos/{id}', [CoursesController::class, 'destroy'])->name('course.destroy');

    Route::get('/add-quiz', function () {
        return Inertia::render('AddQuiz');
    })->name('addQuiz');

    Route::post('/addquiz', [QuizsController1::class, 'store'])->name('quiz.store');
    Route::post('/api/update-test/{id}', [QuizsController1::class, 'updateTest'])->name('update-test');

    Route::get('/manage-quiz', [QuizsController1::class, 'index'])->name('manageQuiz');
    Route::post('/api/update-question/{id}', [QuizsController1::class, 'updateQuestion'])->name('question.update');
    Route::post('/api/update-answer/{id}', [QuizsController1::class, 'updateAnswer'])->name('answer.update');

});



// Route::middleware(['auth', 'role:student'])->group(function () {
//     // Маршруты, доступные только студентам
//     Route::get('/', function () {
//         $courses = Course::with('user')->orderBy('id', 'DESC')->get();
//         return Inertia::render('Welcome', [
//             'courses' => $courses,
//         ]);
//     })->name('home');

// });

Route::get('auth/{provider}/redirect', [ProviderController::class, 'redirect']);
Route::get('auth/{provider}/callback', [ProviderController::class, 'callback']);

require __DIR__.'/auth.php';
