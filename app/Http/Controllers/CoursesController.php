<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Courses;
use App\Models\Course;
use App\Models\MyCourses;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class CoursesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */

     public function index()
     {
         $courses = Courses::with('user')->get();
         return inertia('Welcome', ['courses' => $courses]);
     }

     public function store(Request $request)
     {
        $userId = auth()->id(); // Получаем ID текущего пользователя
         $thumbnail_file = null;

         $course = new Courses;

         $thumbnail_file = $request->file('thumbnail');
         $request->validate([
             'thumbnail' => 'required|mimes:jpg,jpeg,png|max:2048',
         ]);

         $thumbPath = '/course/';

         $extension = $thumbnail_file->getClientOriginalExtension();
         $imageName = 'courseThumbnail' . time() . '.' . $extension;

         $course->title = $request->input('title');
        //  $course->instructor = $request->input('instructor');
         $course->user_id = $userId;
         $course->thumbnail = $thumbPath . $imageName;
        //  $course->rating = 0;
        //  $course->orig_price = $request->input('current_price');
        //  $course->current_price = $request->input('current_price');
         $course->overview = $request->input('course_overview');

         $thumbnail_file->move(public_path() . $thumbPath, $imageName);
         if ($course->save()) {
             $episodes = $request->input('episodes');
             $episodesfile = $request->file('episodes');

             if($episodes){
                 foreach ($episodes as $index => $episode) {
                     // Handle thumbnail image upload
                     if (isset($episodesfile[$index]['thumbnail'])) {
                         $thumbnailFile = $episodesfile[$index]['thumbnail'];

                         // Get the original extension of the uploaded thumbnail
                         $extension = $thumbnailFile->getClientOriginalExtension();

                         // Generate a unique name for the thumbnail
                         $thumbnailName = 'episodeThumbnail' . $index . time() . '.' . $extension;

                         // Move the thumbnail file to the appropriate directory
                         $thumbnailFile->move(public_path('/videos/thumbnails/'), $thumbnailName);

                         $thumbnailNamePath = '/videos/thumbnails/' . $thumbnailName;
                     } else {
                         $thumbnailNamePath = "";
                     }

                     // Handle video upload
                     if (isset($episodesfile[$index]['video'])) {
                         $videoFile = $episodesfile[$index]['video'];

                         // Get the original extension of the uploaded video
                         $extension = $videoFile->getClientOriginalExtension();

                         // Generate a unique name for the video
                         $videoName = 'episodeName' . $index . time() . '.' . $extension;

                         // Move the video file to the appropriate directory
                         $videoFile->move(public_path('/videos/'), $videoName);

                         $videoPath = '/videos/' . $videoName;
                     } else {
                         $videoPath = "";
                     }

                     // Insert the episode data into the database
                     $episodeId = DB::table('course_episodes')->insertGetId([
                         'course_id' => $course->id,
                         'episode_number' => $index + 1,
                         'episode_title' => $episode['title'],
                         'episode_description' => $episode['description'],
                         'episode_thumbnail' => $thumbnailNamePath,
                         'episode_video' => $videoPath,
                         'created_at' => now(),
                         'updated_at' => now(),
                     ]);

                     // Insert topics for the episode
                     if (isset($episode['topics']) && is_array($episode['topics'])) {
                         foreach ($episode['topics'] as $topicIndex => $topic) {
                             $topicId = DB::table('topics')->insertGetId([
                                 'course_episodes_id' => $episodeId,
                                 'topic_title' => $topic['title'],
                                 'created_at' => now(),
                                 'updated_at' => now(),
                             ]);

                             // Insert objects for the topic
                             if (isset($topic['objects']) && is_array($topic['objects'])) {
                                foreach ($topic['objects'] as $objectIndex => $object) {
                                    if ($object['type'] === 'text') {
                                        DB::table('topic_objects')->insert([
                                            'topic_id' => $topicId,
                                            'type' => 'text',
                                            'content' => $object['content'],
                                            'created_at' => now(),
                                            'updated_at' => now(),
                                        ]);
                                    } elseif ($object['type'] === 'file') {
                                        $file = $episodesfile[$index]['topics'][$topicIndex]['objects'][$objectIndex]['file'];

                                        // Get the original extension of the uploaded file
                                        $extension = $file->getClientOriginalExtension();

                                        // Generate a unique name for the file
                                        $fileName = 'objectFile' . $index . $topicIndex . $objectIndex . time() . '.' . $extension;

                                        // Move the file to the appropriate directory
                                        $file->move(public_path('/files/'), $fileName);

                                        $filePath = '/files/' . $fileName;

                                        DB::table('topic_objects')->insert([
                                            'topic_id' => $topicId,
                                            'type' => 'file',
                                            'file_path' => $filePath,
                                            'created_at' => now(),
                                            'updated_at' => now(),
                                        ]);
                                    } elseif ($object['type'] === 'test') {
                                        DB::table('topic_objects')->insert([
                                            'topic_id' => $topicId,
                                            'type' => 'test',
                                            'test_id' => $object['test_id'],
                                            'created_at' => now(),
                                            'updated_at' => now(),
                                        ]);
                                    }elseif ($object['type'] === 'task') {
                                        DB::table('topic_objects')->insert([
                                            'topic_id' => $topicId,
                                            'type' => 'task',
                                            'content' => $object['content'],
                                            'created_at' => now(),
                                            'updated_at' => now(),
                                        ]);
                                    }
                                }
                            }
                         }
                     }
                 }
             }
         }
         return redirect()->route('course.show', $course['id']);
     }


     public function enroll(Request $request, $courseId)
    {
        $userId = auth()->id();

        // Проверяем, есть ли уже запись для данного пользователя и курса
        $existingEnrollment = MyCourses::where('user_id', $userId)->where('course_id', $courseId)->first();

        if (!$existingEnrollment) {
            // Записываем пользователя в курс
            MyCourses::create([
                'user_id' => $userId,
                'course_id' => $courseId,
                'status' => 'enrolled',
            ]);
        }

        // Перенаправляем пользователя на страницу Coursess.vue
        return redirect()->route('course.detail', ['id' => $courseId]);
    }

    public function show($id)
    {
        $userId = auth()->id();

        // Проверяем, записан ли пользователь на данный курс
        $enrolled = MyCourses::where('user_id', $userId)->where('course_id', $id)->exists();

        if ($enrolled) {
            // Перенаправляем пользователя на страницу списка курсов, если он уже записан
            return redirect()->route('course.detail', ['id' => $id]);
        }

        return Inertia::render('Course', [
            'course' => Courses::find($id),
            'episodes' => DB::table('course_episodes')->where('course_id', $id)->get()
        ]);
    }

    // public function watchepisode($id){
    //     $episode_details = DB::table('course_episodes')->find($id);

    //     return Inertia::render('WatchEpisode', [
    //         'episode_details' => DB::table('course_episodes')->select('course_episodes.*','user_id')
    //             ->join('courses', 'course_episodes.course_id', '=', 'courses.id')
    //             ->where('course_episodes.id',$id)->first(),
    //         'other_episodes' => DB::table('course_episodes')->where('course_id', $episode_details->course_id)->get()
    //     ]);
    // }
    public function destroy($id)
{
    $course = Courses::find($id);

    if (!$course) {
        return redirect()->route('manageCourses')->with('error', 'Course not found');
    }

    $course_id = $course->id;

    $course_episodes = DB::table('course_episodes')->where('course_id', $course_id)->get(); // Course Episodes

    // delete all episodes
    if ($course_episodes) {
        foreach ($course_episodes as $episode) {
            // delete the episode thumbnail
            @unlink(public_path() . $episode->episode_thumbnail);

            // delete the episode video
            @unlink(public_path() . $episode->episode_video);

            // delete the database record
            DB::table('course_episodes')->where('id', $episode->id)->delete();
        }
    }

    // course thumbnail delete
    @unlink(public_path() . $course->thumbnail);

    $course->delete(); // delete the course in the actual database

    return redirect()->route('manageCourses')->with('success', 'Course deleted successfully');
}


    public function viewCourse($id)
    {
        $course = Course::with([
            'episodes.topics' => function ($query) {
                $query->whereHas('objects', function ($q) {
                    $q->where('type', 'task');
                });
            },
            'episodes.topics.responses.user'
        ])->findOrFail($id);

        return Inertia::render('ViewCourse', [
            'course' => $course,
        ]);
    }
}
