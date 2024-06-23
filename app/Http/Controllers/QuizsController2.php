<?php

namespace App\Http\Controllers;

use App\Models\Tests;
use App\Models\Attempts;
use App\Models\Questions;
use App\Models\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class QuizsController2 extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function showSingleQuiz($id)
    {
        $userId = auth()->id();
        $test = Tests::with(['questions.options'])->findOrFail($id);
        $userAttempts = Attempts::where('user_id', $userId)->where('test_id', $id)->get();

        return Inertia::render('QuizOnly', [
            'test' => $test,
            'userAttempts' => $userAttempts,
        ]);
    }

    public function index()
    {
        $userId = auth()->id();
        $tests = Tests::all();
        $userAttempts = Attempts::where('user_id', $userId)->get();
        return Inertia::render('Quizzes', ['tests' => $tests, 'userAttempts' => $userAttempts]);
    }

    public function show($id, Request $request)
    {
        $test = Tests::with(['questions.options'])->findOrFail($id);
        $attemptId = $request->input('attempt_id');
        return Inertia::render('Quiz', ['test' => $test, 'attempt_id' => $attemptId]);
    }

    public function submit(Request $request, $id)
    {
        $test = Tests::with(['questions.options'])->findOrFail($id);
        $userId = auth()->id(); // Получаем ID текущего пользователя

        $userAnswers = $request->input('answers');
        $correctAnswersCount = 0;

        $latestAttempt = Attempts::where('user_id', $userId)
        ->where('test_id', $id)
        ->orderBy('created_at', 'desc')
        ->first();

        foreach ($test->questions as $questionIndex => $question) {
            // Получаем все правильные варианты ответа для вопроса
            $correctAnswers = DB::table('correct_answers')
                ->where('question_id', $question->id)
                ->pluck('option_id')
                ->toArray();

            // Проверка для вопросов с одним правильным ответом
            if ($question->type === 'single') {
                if ($correctAnswers && isset($userAnswers[$questionIndex]) && $userAnswers[$questionIndex] == $correctAnswers[0]) {
                    $correctAnswersCount++;
                }
            }
            // Проверка для вопросов с несколькими правильными ответами
            else if ($question->type === 'multiple') {
                if (isset($userAnswers[$questionIndex]) && is_array($userAnswers[$questionIndex])) {
                    $selectedAnswers = $userAnswers[$questionIndex];
                    // Проверяем, что все правильные ответы выбраны и нет лишних ответов
                    if (count(array_diff($correctAnswers, $selectedAnswers)) === 0 && count(array_diff($selectedAnswers, $correctAnswers)) === 0) {
                        $correctAnswersCount++;
                    }
                }
            }
        }

        $totalQuestions = $test->questions->count();
        $percentage = ($totalQuestions > 0) ? ($correctAnswersCount / $totalQuestions) * 100 : 0;

        // Определяем оценку на основе процентов
        $grade = 2; // Default grade if no thresholds are met
        if ($percentage >= $test->grade_5) {
            $grade = 5;
        } else if ($percentage >= $test->grade_4) {
            $grade = 4;
        } else if ($percentage >= $test->grade_3) {
            $grade = 3;
        }

        // Сохраняем результат в таблицу evaluations
        DB::table('evaluations')->insert([
            'user_id' => $userId,
            'test_id' => $test->id,
            'value' => $grade,
            'percent' => $percentage,
            'attempts_id' => $latestAttempt->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $result = [
            'totalQuestions' => $totalQuestions,
            'correctAnswers' => $correctAnswersCount,
            'percentage' => $percentage,
            'grade' => $grade,
        ];

        return Inertia::render('QuizResult', [
            'test' => $test,
            'result' => $result,
        ]);
    }

    public function startQuiz(Request $request, $id)
    {
        $userId = auth()->id();
        $test = Tests::findOrFail($id);

        $usedAttemptsCount = Attempts::where('user_id', $userId)
            ->where('test_id', $id)
            ->count();

        if ($usedAttemptsCount >= $test->attempts) {
            return redirect()->back()->withErrors(['msg' => 'Все попытки использованы.']);
        }

        $newAttempt = Attempts::create([
            'user_id' => $userId,
            'test_id' => $id,
            'attempt' => $usedAttemptsCount + 1,
            'status' => 'progress'
        ]);

        return redirect()->route('quizzes.show', ['id' => $id, 'attempt_id' => $newAttempt->id]);
    }
}
