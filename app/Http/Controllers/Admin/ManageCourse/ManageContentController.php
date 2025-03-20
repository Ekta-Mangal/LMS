<?php

namespace App\Http\Controllers\Admin\ManageCourse;

use App\Http\Controllers\Controller;
use App\Imports\QuizImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;


class ManageContentController extends Controller
{
    public function list()
    {
        try {
            $Contents = DB::table('module_master')
                ->leftJoin('course_master', 'module_master.course_id', '=', 'course_master.id')
                ->select(
                    'module_master.id as module_id',
                    'module_master.title as module_name',
                    'module_master.prerequisite_required',
                    'course_master.title as course_name',
                )
                ->orderBy('module_master.id')
                ->get();

            return view('admin.managecontent.list', compact('Contents'));
        } catch (Exception $e) {
            return back()->with("error", "Something Went Wrong");
        }
    }

    public function add()
    {
        try {
            $courses = DB::table('course_master')->select('id', 'title')->distinct()->get();
            $html = view('admin.managecontent.add', compact('courses'))->render();
            return response()->json(['html' => $html, 'success' => true]);
        } catch (Exception $e) {
            return back()->with("error", "Something Went Wrong");
        }
    }

    public function postadd(Request $request)
    {
        try {
            // dd($request->all());
            $userId = Auth::user()->empid;
            $validator = Validator::make($request->all(), [
                'course_id' => 'required',
                'module_id' => 'required',
                'content_type' => 'required',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            if ($request->content_type === 'pdf') {
                if ($request->hasFile('pdf_file')) {
                    $file = $request->file('pdf_file');
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $sanitizedFileName = preg_replace('/[^A-Za-z0-9]/', '_', $originalName) . '.' . $file->getClientOriginalExtension();

                    // Define the correct storage path
                    $folderPath = "uploads/content/course_{$request->course_id}/module_{$request->module_id}/pdf";
                    $fullPath = public_path($folderPath); // Ensure it's in the public folder

                    // Create directory if it doesn't exist
                    if (!file_exists($fullPath)) {
                        mkdir($fullPath, 0777, true);
                    }

                    // Move the file to the correct location
                    $file->move($fullPath, $sanitizedFileName);

                    // Save in DB
                    DB::table('file_content_master')->insert([
                        'module_id' => $request->module_id,
                        'title' => $sanitizedFileName,
                        'type' => "pdf",
                        'path' => $folderPath . '/' . $sanitizedFileName,
                        'created_by' => $userId,
                        'created_at' => now()
                    ]);
                }
            } else if ($request->content_type === 'audio' || $request->content_type === 'video') {
                $file = $request->file($request->content_type === 'video' ? 'video_file' : 'audio_file');
                $originalName = $file->getClientOriginalName();
                $cleanedName = preg_replace('/[^A-Za-z0-9.]/', '_', $originalName);

                $folderPath = "uploads/content/course_{$request->course_id}/module_{$request->module_id}/" . ($request->content_type === 'video' ? 'videos' : 'audios');
                $fullPath = public_path($folderPath);

                // Create directory if it doesn't exist
                if (!file_exists($fullPath)) {
                    mkdir($fullPath, 0777, true);
                }

                // Move the file to the correct location
                $file->move($fullPath, $cleanedName);

                // Insert data into file_content_master table
                DB::table('file_content_master')->insert([
                    'module_id' => $request->module_id,
                    'title' => pathinfo($cleanedName, PATHINFO_FILENAME),
                    'type' => $request->content_type,
                    'path' => "$folderPath/$cleanedName",
                    'created_by' => $userId,
                    'created_at' => now(),
                ]);
            } else if ($request->content_type === 'text') {
                $validator = Validator::make($request->all(), [
                    'text_title' => 'required',
                    'text_content' => 'required',
                ]);
                if ($validator->fails()) {
                    return back()->withErrors($validator)->withInput();
                }
                DB::table('content_master')->insert([
                    'module_id' => $request->module_id,
                    'title' => $request->text_title,
                    'text' => $request->text_content,
                    'created_by' => $userId,
                    'created_at' => now()
                ]);
            } else if ($request->content_type === 'quiz') {
                $validator = Validator::make($request->all(), [
                    'quiz_title' => 'required',
                    'quiz_description' => 'required',
                    'passing_marks' => 'required',
                    'allow_attempts' => 'required',
                ]);
                if ($validator->fails()) {
                    return back()->withErrors($validator)->withInput();
                }
                $quiz_id = DB::table('quiz_master')->insertGetId([
                    'module_id' => $request->module_id,
                    'course_id' => $request->course_id,
                    'quiz_title' => $request->quiz_title,
                    'quiz_description' => $request->quiz_description,
                    'passing_marks' => $request->passing_marks,
                    'allow_attempts' => $request->allow_attempts,
                    'created_by' => $userId,
                    'created_at' => now()
                ]);
                Excel::import(new QuizImport($request->module_id, $quiz_id), request()->file('file'));
            } else {
                return back()->with("error", "Not a valid Content Type");
            }
            return back()->with('success', 'Content Saved Successfully');
        } catch (Exception $e) {
            return back()->with("error", "Something Went Wrong: " . $e->getMessage());
        }
    }

    public function edit(Request $request)
    {
        try {
            $module_id = $request->module_id;

            // Fetch data from all three tables
            $files = DB::table('file_content_master')->where('module_id', $module_id)->get();
            $contents = DB::table('content_master')->where('module_id', $module_id)->get();

            // Get module title and course title
            $module = DB::table('module_master')->where('id', $module_id)->first();
            $module_title = $module->title ?? null;
            $module_id = $module->id;

            $course = DB::table('course_master')->where('id', $module->course_id ?? null)->first();
            $course_title = $course->title ?? null;
            $course_id = $course->id;

            // Pass data to the view
            $html = view('admin.managecontent.edit', compact('files', 'contents', 'module_title', 'course_title', 'module_id', 'course_id'))->render();

            return response()->json(['html' => $html, 'success' => true]);
        } catch (Exception $e) {
            return back()->with("error", "Something Went Wrong");
        }
    }

    public function editquiz(Request $request)
    {
        try {
            $module_id = $request->module_id;
            $quizData = DB::table('quiz_master as q')
                ->leftJoin('question_master as qm', 'q.id', '=', 'qm.quiz_id')
                ->where('q.module_id', $module_id)
                ->select(
                    'q.id as quiz_id',
                    'q.module_id',
                    'q.course_id',
                    'q.quiz_title',
                    'q.quiz_description',
                    'q.passing_marks',
                    'q.allow_attempts',
                    'q.created_by',
                    'q.created_at',
                    'qm.id as question_id',
                    'qm.question',
                    'qm.option1',
                    'qm.option2',
                    'qm.option3',
                    'qm.option4',
                    'qm.correct_ans'
                )
                ->get()
                ->groupBy('quiz_id')
                ->map(function ($quiz) {
                    $quizDetails = $quiz->first();
                    return [
                        'quiz_id' => $quizDetails->quiz_id,
                        'module_id' => $quizDetails->module_id,
                        'course_id' => $quizDetails->course_id,
                        'quiz_title' => $quizDetails->quiz_title,
                        'quiz_description' => $quizDetails->quiz_description,
                        'passing_marks' => $quizDetails->passing_marks,
                        'allow_attempts' => $quizDetails->allow_attempts,
                        'created_by' => $quizDetails->created_by,
                        'created_at' => $quizDetails->created_at,
                        'questions' => $quiz->map(function ($q) {
                            return [
                                'question_id' => $q->question_id,
                                'question' => $q->question,
                                'option1' => $q->option1,
                                'option2' => $q->option2,
                                'option3' => $q->option3,
                                'option4' => $q->option4,
                                'correct_ans' => $q->correct_ans,
                            ];
                        })->values(),
                    ];
                })->values();
            $html = view('admin.managecontent.editquiz', compact('module_id', 'quizData'))->render();
            return response()->json(['html' => $html, 'success' => true]);
        } catch (Exception $e) {
            return back()->with("error", "Something Went Wrong");
        }
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'module_id' => 'required',
            'course_id' => 'required',
        ]);

        $userId = Auth::user()->empid;

        // Handle PDFs Upload
        if ($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $sanitizedFileName = preg_replace('/[^A-Za-z0-9]/', '_', $originalName) . '.' . $file->getClientOriginalExtension();
            // Define the correct storage path
            $folderPath = "uploads/content/course_{$request->course_id}/module_{$request->module_id}/pdf";
            $fullPath = public_path($folderPath); // Ensure it's in the public folder

            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0777, true);
            }

            $file->move($fullPath, $sanitizedFileName);

            DB::table('file_content_master')->insert([
                'module_id' => $request->module_id,
                'title' => $sanitizedFileName,
                'type' => 'pdf',
                'path' => "$folderPath/$sanitizedFileName",
                'created_by' => $userId,
                'created_at' => now(),
            ]);
        }

        // Handle Audios Upload
        if ($request->hasFile('audio_file')) {
            $file = $request->file('audio_file');
            $cleanedName = preg_replace('/[^A-Za-z0-9.]/', '_', $file->getClientOriginalName());
            $folderPath = "uploads/content/course_{$request->course_id}/module_{$request->module_id}/audios";
            $fullPath = public_path($folderPath);

            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0777, true);
            }

            $file->move($fullPath, $cleanedName);

            DB::table('file_content_master')->insert([
                'module_id' => $request->module_id,
                'title' => pathinfo($cleanedName, PATHINFO_FILENAME),
                'type' => 'audio',
                'path' => "$folderPath/$cleanedName",
                'created_by' => $userId,
                'created_at' => now(),
            ]);
        }

        // Handle Videos Upload
        if ($request->hasFile('video_file')) {
            $file = $request->file('video_file');
            $cleanedName = preg_replace('/[^A-Za-z0-9.]/', '_', $file->getClientOriginalName());
            $folderPath = "uploads/content/course_{$request->course_id}/module_{$request->module_id}/videos";
            $fullPath = public_path($folderPath);

            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0777, true);
            }

            $file->move($fullPath, $cleanedName);

            DB::table('file_content_master')->insert([
                'module_id' => $request->module_id,
                'title' => pathinfo($cleanedName, PATHINFO_FILENAME),
                'type' => 'video',
                'path' => "$folderPath/$cleanedName",
                'created_by' => $userId,
                'created_at' => now(),
            ]);
        }

        // Handle Text Content
        if ($request->text_id && $request->text_title && $request->text_content) {
            DB::table('content_master')
                ->where('id', $request->text_id)
                ->update([
                    'title' => $request->text_title,
                    'text' => $request->text_content,
                    'updated_by' => $userId,
                    'updated_at' => now(),
                ]);
        }
        if ($request->new_text_title && $request->new_text_content) {
            DB::table('content_master')->insert([
                'module_id' => $request->module_id,
                'title' => $request->new_text_title,
                'text' => $request->new_text_content,
                'created_by' => $userId,
                'created_at' => now(),
            ]);
        }
        return back()->with('success', 'Content updated successfully.');
    }

    public function updatequiz(Request $request)
    {
        $userId = Auth::user()->empid;
        $validated = $request->validate([
            'quiz_id' => 'required',
            'module_id' => 'required',
            'quiz_title' => 'required',
            'quiz_description' => 'required',
            'passing_marks' => 'required',
            'allow_attempts' => 'required',
            'answers' => 'required|array',
        ]);

        try {
            // Update quiz_master table
            DB::table('quiz_master')
                ->where('module_id', $request->module_id)
                ->where('id', $request->quiz_id)
                ->update([
                    'quiz_title' => $request->quiz_title,
                    'quiz_description' => $request->quiz_description,
                    'passing_marks' => $request->passing_marks,
                    'allow_attempts' => $request->allow_attempts,
                    'updated_by' => $userId,
                    'updated_at' => now(),
                ]);

            // Update question_master table for each answer
            foreach ($request->answers as $questionId => $correctAns) {
                DB::table('question_master')
                    ->where('id', $questionId)
                    ->update(['correct_ans' => $correctAns]);
            }

            return back()->with('success', 'Quiz and answers updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update quiz: ' . $e->getMessage());
        }
    }

    public function removeFile(Request $request)
    {
        try {
            $type = $request->input('type');
            $id = $request->input('id');
            if (!$id) {
                return response()->json(['message' => 'Invalid request, missing ID', 'status' => 'error']);
            }

            if ($type === 'audio' || $type === 'video' || $type === 'pdf') {
                $files = DB::table('file_content_master')
                    ->where('id', $id)
                    ->pluck('path');
                foreach ($files as $file) {
                    $filePath = public_path($file);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
                DB::table('file_content_master')->where('id', $id)->delete();
            } elseif ($type === 'text') {
                DB::table('content_master')->where('id', $id)->delete();
            } else {
                return response()->json(['message' => 'Invalid file type', 'status' => 'error']);
            }

            return response()->json(['message' => 'File Removed', 'status' => 'success']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Something Went Wrong', 'status' => 'error']);
        }
    }

    public function delete(Request $request)
    {
        try {
            $module_id = $request->input('module_id');
            if (!$module_id) {
                return response()->json(['message' => 'Invalid request, missing module ID', 'status' => 'error']);
            }

            $files = DB::table('file_content_master')
                ->where('module_id', $module_id)
                ->pluck('path');
            foreach ($files as $file) {
                $filePath = public_path($file);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            DB::table('file_content_master')->where('module_id', $module_id)->delete();
            DB::table('content_master')->where('module_id', $module_id)->delete();
            return response()->json(['message' => 'Files and related data deleted successfully', 'status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong: ' . $e->getMessage(), 'status' => 'error']);
        }
    }


    public function deleteQuiz(Request $request)
    {
        try {
            $module_id = $request->input('module_id');

            if (!$module_id) {
                return response()->json(['message' => 'Invalid request, missing module ID', 'status' => 'error']);
            }

            DB::table('quiz_master')->where('module_id', $module_id)->delete();
            DB::table('question_master')->where('module_id', $module_id)->delete();

            return response()->json(['message' => 'All related Quiz and Questions deleted successfully', 'status' => 'success']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Something Went Wrong', 'status' => 'error']);
        }
    }
}