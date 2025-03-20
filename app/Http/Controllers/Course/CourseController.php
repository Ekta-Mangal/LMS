<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function view(Request $request)
    {
        try {
            $user = Auth::user()->empid;
            $course_id = $request->id;
            $coursedetails = Course::find($course_id);
            // Check if the user is already enrolled in the course
            $alreadyEnrolled = DB::table('user_progress')
                ->where('empid', $user)
                ->where('course_id', $course_id)
                ->exists();
            if (!$coursedetails) {
                return response()->json(['error' => 'Course not found', 'success' => false]);
            }
            $moduletitles = Module::where('course_id', $course_id)->pluck('title');

            $html = view('course.viewdetails', compact('coursedetails', 'moduletitles', 'alreadyEnrolled'))->render();
            return response()->json(['html' => $html, 'success' => true]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong', 'success' => false]);
        }
    }

    public function viewmodules()
    {
        try {
            $user = Auth::user()->empid;
            $role = Auth::user()->role;
            $badge = Auth::user()->badge_level;

            // Fetch modules with approval status
            $modules = DB::table('user_progress')
                ->where('empid', $user)
                ->join('module_master', 'user_progress.module_id', '=', 'module_master.id')
                ->select('*')
                ->get();

            $upgrade = DB::table('users')
                ->where('empid', $user)
                ->value('upgrade_level_status');

            // Track completed modules
            $completedModules = $modules->where('approval_status', 'Approved')->pluck('id')->toArray();

            // Determine which modules should be locked based on prerequisites
            $modules = $modules->map(function ($module) use ($completedModules) {
                if ($module->prerequisite_required === 'Yes') {
                    $prerequisites = explode(',', $module->prerequisite_module);
                    $module->isLocked = !empty(array_diff($prerequisites, $completedModules));
                } else {
                    $module->isLocked = false;
                }
                return $module;
            });

            if ($role === "L1") {
                // Fetch all L1 courses
                $L1Courses = DB::table('course_master')
                    ->where('level', 'L1')
                    ->pluck('id')
                    ->toArray();

                // Get the course statuses for L1 courses from user_progress
                $courseStatuses = DB::table('user_progress')
                    ->where('empid', $user)
                    ->whereIn('course_id', $L1Courses)
                    ->pluck('course_status')
                    ->toArray();

                $NewCourseUnlocked = !in_array('Pending', $courseStatuses) && !in_array('In Progress', $courseStatuses) && !empty($courseStatuses);

                // dd($modules, $NewCourseUnlocked, $upgrade, $badge);
                return view('L1.modules', compact('modules', 'NewCourseUnlocked', 'upgrade', 'badge'));
            } else if ($role === "L2") {
                // Fetch all L2 courses
                $L2Courses = DB::table('course_master')
                    ->where('level', 'L2')
                    ->pluck('id')
                    ->toArray();

                // Get the course statuses for L2 courses from user_progress
                $courseStatuses = DB::table('user_progress')
                    ->where('empid', $user)
                    ->whereIn('course_id', $L2Courses)
                    ->pluck('course_status')
                    ->toArray();

                $NewCourseUnlocked = !in_array('Pending', $courseStatuses) && !in_array('In Progress', $courseStatuses) && !empty($courseStatuses);
                // dd($modules, $NewCourseUnlocked, $upgrade, $badge);
                return view('L2.modules', compact('modules', 'NewCourseUnlocked', 'upgrade', 'badge'));
            } else if ($role === "L3") {
                // Fetch all L3 courses
                $L3Courses = DB::table('course_master')
                    ->where('level', 'L3')
                    ->pluck('id')
                    ->toArray();

                // Get the course statuses for L3 courses from user_progress
                $courseStatuses = DB::table('user_progress')
                    ->where('empid', $user)
                    ->whereIn('course_id', $L3Courses)
                    ->pluck('course_status')
                    ->toArray();

                $NewCourseUnlocked = !in_array('Pending', $courseStatuses) && !in_array('In Progress', $courseStatuses) && !empty($courseStatuses);
                return view('L3.modules', compact('modules', 'NewCourseUnlocked', 'upgrade', 'badge'));
            } else {
                return back()->with("error", "Unauthorized Access");
            }
        } catch (Exception $e) {
            return back()->with("error", "Something Went Wrong");
        }
    }

    public function upgradeLevel(Request $request)
    {
        try {
            $user = Auth::user()->empid; // Get the logged-in user's empid

            if ($user) {
                DB::table('users')
                    ->where('empid', $user)
                    ->update([
                        'upgrade_level_status' => 'Waiting',
                        'submit_for_approval' => now()
                    ]);

                return response()->json(['success' => true, 'message' => 'Upgrade request submitted successfully.']);
            }
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong'], 500);
        }
    }
}