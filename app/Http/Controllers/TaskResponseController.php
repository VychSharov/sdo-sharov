<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\TaskResponse;

class TaskResponseController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'response_text' => 'nullable|string',
            'file' => 'nullable|file|mimes:jpeg,jpg,png,gif,svg,bmp,mp4,webm,ogg,pdf,doc,docx',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('task_responses', 'public');
        }

        TaskResponse::create([
            'topic_id' => $request->topic_id,
            'user_id' => auth()->id(),
            'response_text' => $request->response_text,
            'file_path' => $filePath,
        ]);

        return response()->json(['message' => 'Task response submitted successfully.'], 200);
    }
}
