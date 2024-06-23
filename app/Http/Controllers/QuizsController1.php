<?php

namespace App\Http\Controllers;

use App\Models\Tests;
use App\Models\Questions;
use App\Models\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class QuizsController1 extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    $userId = auth()->id(); // Получаем ID текущего пользователя
    $request->validate([
    'test_name' => 'required|string',
    'description' => 'required|string',
    'questions' => 'required|array',
    'duration' => 'required|integer|min:1',
    'grade_3' => 'required|integer|min:0|max:100',
    'grade_4' => 'required|integer|min:0|max:100',
    'grade_5' => 'required|integer|min:0|max:100',
    ]);
    DB::beginTransaction();

    try {
        $test = new Tests();
        $test->test_name = $request->input('test_name');
        $test->description = $request->input('description');
        $test->duration = $request->duration;
        $test->grade_3 = $request->grade_3;
        $test->grade_4 = $request->grade_4;
        $test->grade_5 = $request->grade_5;
        $test->attempts = $request->attempts;
        $test->user_id = $userId; // Сохраняем ID текущего пользователя


        if (!$test->save()) {
            throw new \Exception('Failed to save test.');
        }

        foreach ($request->input('questions') as $question) {
            $questionId = DB::table('questions')->insertGetId([
                'test_id' => $test->id,
                'question_text' => $question['question_text'],
                'type' => $question['type'], // Сохранение типа вопроса
                'created_at' => now(),
                'updated_at' => now(),

            ]);

            foreach ($question['options'] as $option) {
                $optionId = DB::table('options')->insertGetId([
                    'question_id' => $questionId,
                    'option_text' => $option['option_text'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Логирование значения correct
                $correct = filter_var($option['correct'], FILTER_VALIDATE_BOOLEAN);
                // \Log::info('Option Correct Value: ', ['correct' => $correct]);

                if ($correct) {
                    DB::table('correct_answers')->insert([
                        'question_id' => $questionId,
                        'option_id' => $optionId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        DB::commit();

        return response()->json(['message' => 'Quiz added successfully'], 201);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['message' => 'Failed to add quiz', 'error' => $e->getMessage()], 500);
    }
}


    public function index()
    {
        $tests = Tests::all();

        foreach ($tests as &$test) {
            $questions = Questions::where('test_id', $test->id)->get();

            foreach ($questions as &$question) {
                $question->options = Options::where('question_id', $question->id)->get();
            }

            $test->questions = $questions;
        }

        return Inertia::render('ManageQuiz', [
            'tests' => $tests
        ]);
    }
    public function updateQuestion(Request $request, $id)
    {
        $question = Questions::findOrFail($id);
        $question->question_text = $request->input('question_text');
        $question->save();

        return response()->json(['success' => true]);
    }

    public function updateAnswer(Request $request, $id)
    {
        $option = Options::findOrFail($id);
        $option->option_text = $request->input('option_text');
        $option->save();

        return response()->json(['success' => true]);
    }

    public function updateTest(Request $request, $id)
    {
        $request->validate([
            'test_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
        ]);

        $test = Tests::findOrFail($id);
        $test->test_name = $request->test_name;
        $test->description = $request->description;
        $test->duration = $request->duration;
        $test->save();

        return response()->json(['status' => 'success']);
    }

    public function show($id)
    {
        $test = Tests::with(['questions.options'])->findOrFail($id);
        return response()->json($test);

    }

    public function submit(Request $request, $id)
{
    $test = Tests::with('questions.options')->findOrFail($id);
    $userAnswers = $request->input('answers');
    $correctAnswersCount = 0;

    foreach ($test->questions as $questionIndex => $question) {
        // Найти правильный вариант ответа для текущего вопроса
        $correctOption = DB::table('correct_answers')
            ->where('question_id', $question->id)
            ->first();

        // Сравнить ответ пользователя с правильным вариантом
        if ($correctOption && isset($userAnswers[$questionIndex]) && $userAnswers[$questionIndex] == $correctOption->option_id) {
            $correctAnswersCount++;
        }
    }

    $result = [
        'totalQuestions' => $test->questions->count(),
        'correctAnswers' => $correctAnswersCount,
    ];

    return response()->json($result);
}


}
