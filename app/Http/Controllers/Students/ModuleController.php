<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;

class ModuleController extends Controller
{
    public function view(Request $request)
    {
        try {
            $user = Auth::user()->empid;
            $module_id = $request->id;

            $moduleDetails = DB::table('module_master')
                ->join('course_master', 'module_master.course_id', '=', 'course_master.id')
                ->where('module_master.id', $module_id)
                ->select(
                    'module_master.*',
                    'course_master.title as course_title'
                )
                ->first();


            $userProgress = DB::table('user_progress')
                ->where('module_id', $module_id)
                ->where('empid', $user)
                ->first();
            // dd($moduleDetails);

            $html = view('Students.course.moduledetails', compact('moduleDetails', 'userProgress'))->render();

            return response()->json(['html' => $html, 'success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong', 'success' => false]);
        }
    }

    public function start(Request $request)
    {
        try {
            $user = Auth::user()->empid;
            $module_id = $request->id;
            $module = DB::table('module_master')
                ->join('course_master', 'module_master.course_id', '=', 'course_master.id')
                ->where('module_master.id', $module_id)
                ->select(
                    'module_master.*',
                    'course_master.title as course_title'
                )
                ->first();


            // Determine the view based on prerequisite requirement
            if ($module->prerequisite_required === 'Yes') {
                $moduleDetails = DB::table('quiz_master')
                    ->where('module_id', $module_id)
                    ->get();

                // Fetch user assessments for the quizzes
                $userAssessments = DB::table('user_assessment')
                    ->where('user_id', $user)
                    ->whereIn('quiz_id', $moduleDetails->pluck('id'))
                    ->get()->keyBy('quiz_id');;

                foreach ($moduleDetails as $test) {
                    if (!isset($userAssessments[$test->id])) {
                        // No previous attempt, insert a new record
                        DB::table('user_assessment')->insert([
                            'user_id' => $user,
                            'quiz_id' => $test->id,
                            'status' => 'Pending',
                            'attempts' => 0,
                            'result' => null
                        ]);
                        $test->assessment_status = 'Pending';
                        $test->assessment_result = null;
                    } else {
                        // Use existing assessment details
                        $assessment = $userAssessments[$test->id];
                        $test->assessment_status = $assessment->status;
                        $test->assessment_result = $assessment->result;
                        $test->assessment_attempts = $assessment->attempts;

                        // Check if status is "Waiting" and completed_on is 24+ hours old
                        if ($assessment->status === 'Waiting' && !is_null($assessment->completed_on)) {
                            $completedOn = Carbon::parse($assessment->completed_on);
                            if ($completedOn->diffInHours(Carbon::now()) >= 24) {
                                DB::table('user_assessment')
                                    ->where('quiz_id', $test->id)
                                    ->where('user_id', $user)
                                    ->update(['status' => 'Pending']);
                                $test->assessment_status = 'Pending'; // Update local object as well
                            }
                        }
                    }
                }
                $viewName = 'Students.assessment.tests';
            } else {
                $moduleDetails = DB::table('content_master')->where('module_id', $module_id)->first();
                $viewName = 'Students.content.view';
            }

            // Update module_status in user_progress table
            DB::table('user_progress')
                ->where('module_id', $module_id)
                ->where('empid', $user)
                ->update(['module_status' => 'In Progress']);

            // dd($module, $moduleDetails);
            $html = view($viewName, compact('module', 'moduleDetails'))->render();

            return response()->json(['html' => $html, 'success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong', 'success' => false]);
        }
    }

    public function gettabdata(Request $request)
    {
        try {
            $user = Auth::user()->empid;
            $category = $request->id;
            $module_id = $request->module_id;
            if ($category == 'home') {
                $html = '
                <div class="row align-items-center" id="completionSection">
                    <div class="col">
                        <p style="font-size: 16px; margin-bottom: 0;">
                            <strong>Note:</strong> If Completed, Click on "Mark as Completed"
                        </p>
                    </div>
                    <div class="col-auto">
                        <button id="moduleComplete" onclick="completed(' . $module_id . ')" 
                            class="btn btn-primary" style="padding: 10px 20px; font-size: 16px;">
                            Mark As Completed
                        </button>
                    </div>
                </div>';
                return response()->json(['html' => $html, 'status' => true]);
            } elseif ($category == 'pdf') {
                $contents = DB::table('file_content_master')
                    ->where('module_id', $module_id)
                    ->where('type', 'pdf')
                    ->get();
                $html = view('Students.content.pdf', compact('contents'))->render();
                return response()->json(['html' => $html, 'status' => true]);
            } elseif ($category == 'video') {
                $contents = DB::table('file_content_master')
                    ->where('module_id', $module_id)
                    ->whereIn('type', ['audio', 'video'])
                    ->get();

                if ($contents->isEmpty()) {
                    $contents = collect([]);
                }

                // Get existing audio/video statuses for the user
                $Statuses = DB::table('user_watch_history')
                    ->where('user_id', $user)
                    ->where('module_id', $module_id)
                    ->pluck('watch_status', 'content_id')
                    ->toArray();

                // Insert records into user_watch_history if not already present
                $watchHistoryData = [];
                foreach ($contents as $content) {
                    if (!isset($Statuses[$content->id])) {
                        $watchHistoryData[] = [
                            'user_id' => $user,
                            'module_id' => $module_id,
                            'content_id' => $content->id,
                            'watch_status' => 'Pending'
                        ];
                    }
                    // Assign watch_status to content object for frontend use
                    $content->watch_status = $Statuses[$content->id] ?? 'Pending';
                }

                if (!empty($watchHistoryData)) {
                    DB::table('user_watch_history')->insert($watchHistoryData);
                }
                $html = view('Students.content.videos', compact('module_id', 'contents'))->render();
                return response()->json(['html' => $html, 'status' => true]);
            } elseif ($category == 'text') {
                $contents = DB::table('content_master')
                    ->where('module_id', $module_id)
                    ->get();

                $html = view('Students.content.text', compact('contents'))->render();
                return response()->json(['html' => $html, 'status' => true]);
            } else {
                return response()->json(['message' => 'Something Went Wrong', 'status' => false], 200);
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            return response()->json(['message' => $message, 'status' => false], 200);
        }
    }

    public function update(Request $request)
    {
        try {
            $module_id = $request->id;
            $user_id = Auth::user()->empid;

            $affectedRows = DB::table('user_progress')
                ->where('module_id', $module_id)
                ->where('empid', $user_id)
                ->update([
                    'approval_status' => 'Waiting',
                    'submit_for_approval' => now()
                ]);

            if ($affectedRows > 0) {
                return response()->json(['success' => true, 'message' => 'Module marked as completed.']);
            } else {
                return response()->json(['success' => false, 'message' => 'No record found to update.']);
            }
        } catch (Exception $e) {
            return response()->json(['error' => true, 'message' => 'Something went wrong.']);
        }
    }

    public function videoupdate(Request $request)
    {
        try {
            $user = Auth::user()->empid;
            $content_id = $request->id;
            $module_id = $request->module_id;

            // Update the video status to "Completed"
            $affectedRows = DB::table('user_watch_history')
                ->where('user_id', $user)
                ->where('content_id', $content_id)
                ->where('module_id', $module_id)
                ->update(['watch_status' => 'Completed']);

            if ($affectedRows > 0) {
                return response()->json(['success' => true, 'message' => 'Video marked as completed.']);
            } else {
                return response()->json(['success' => false, 'message' => 'No record found to update.']);
            }
        } catch (Exception $e) {
            return response()->json(['error' => true, 'message' => 'Something went wrong.']);
        }
    }

    public function reattempt(Request $request)
    {
        try {
            $user = Auth::user()->empid;
            $module_id = $request->id;

            // Fetch module details
            $module = DB::table('module_master')->where('id', $module_id)->first();
            $moduleDetails = DB::table('content_master')->where('module_id', $module_id)->first();

            // Update module_status to 'Waiting' for the logged-in user
            DB::table('user_progress')
                ->where('module_id', $module_id)
                ->where('empid', $user)
                ->update(['module_status' => 'Waiting']);

            $html = view('Students.content.view', compact('module', 'moduleDetails'))->render();

            return response()->json(['html' => $html, 'success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong', 'success' => false]);
        }
    }
}