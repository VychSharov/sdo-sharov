<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Course;
use App\Models\Topic;
use App\Models\CourseEpisodes;
use Illuminate\Http\Request;
use App\Models\MyCourses;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class CourseController22 extends Controller
{
     public function show($id)
    {
        $course = Course::with('episodes.topics.objects')->findOrFail($id);
        $episodes = $course->episodes;

        foreach ($episodes as $episode) {
            $episode->isOpen = false;
        }

        return Inertia::render('Coursess', [
            'course' => $course,
            'episodes' => $episodes
        ]);
    }


    public function showTopic($id)
    {
        $topic = Topic::with(['objects.test'])->findOrFail($id);

        return Inertia::render('Topic', [
            'topic' => $topic,
        ]);
    }

    public function unenroll(Request $request, $id)
    {
        $userId = auth()->id();
        MyCourses::where('user_id', $userId)->where('course_id', $id)->delete();

        return redirect()->route('home');
    }
    public function submitResponse(Request $request, $id)
    {
        $request->validate([
            'response_text' => 'nullable|string',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,gif,svg,mp4,webm,ogg,pdf,doc,docx,txt'
        ]);

        $responseText = $request->input('response_text');
        $filePath = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Get the original extension of the uploaded file
            $extension = $file->getClientOriginalExtension();

            // Generate a unique name for the file
            $fileName = 'responseFile' . auth()->id() . '_' . time() . '.' . $extension;

            // Move the file to the appropriate directory
            $file->move(public_path('answers'), $fileName);

            // Set the file path
            $filePath = 'answers/' . $fileName;
        }

        DB::table('task_responses')->insert([
            'topic_id' => $id,
            'user_id' => auth()->id(),
            'response_text' => $responseText,
            'file_path' => $filePath,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json(['message' => 'Response submitted successfully.']);
    }

}
