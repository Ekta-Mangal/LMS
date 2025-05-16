<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function view()
    {
        try {
            $user = Auth::user()->empid;
            $role = Auth::user()->role;

            $Courses = DB::table('course_master')
                ->select('*')
                ->where('publish_date', '<=', date('Y-m-d'))
                ->get();


            // Fetch enrolled courses and their statuses
            $enrolledCoursesData = DB::table('user_progress')
                ->where('empid', $user)
                ->select('course_id', 'course_status')
                ->distinct()
                ->get();

            // Convert to associative arrays
            $enrolledCourses = $enrolledCoursesData->pluck('course_id')->toArray();
            $courseStatuses = $enrolledCoursesData->pluck('course_status', 'course_id')->toArray();
            // dd($courseStatuses);

            //session approval pending count
            $pendingApprovalCounts = DB::table('user_progress')
                ->where('approval_status', 'Waiting')
                ->count();

            //total users course enrollment count
            $distinctUserCount = DB::table('user_progress')
                ->distinct('empid')
                ->count('empid');

            //total users completed course count
            $completedUsersCount = DB::table('user_progress')
                ->where('course_status', 'Completed')
                ->distinct('empid')
                ->count('empid');

            //total users attempting course count
            $inProgressUsersCount = DB::table('user_progress')
                ->where('course_status', 'In Progress')
                ->distinct('empid')
                ->count('empid');

            $Count = DB::table('users')
                ->select('badge_level', 'upgrade_level_status')
                ->get();

            $silverCount = 0;
            $goldCount = 0;
            $platinumCount = 0;
            $silverEligible = 0;
            $goldEligible = 0;
            $platinumEligible = 0;

            foreach ($Count as $user) {
                switch ($user->badge_level) {
                    case 'Silver':
                        if ($user->upgrade_level_status === 'Completed' || $user->upgrade_level_status === 'Waiting') {
                            $silverCount++;
                        } else if ($user->upgrade_level_status === 'Pending') {
                            $silverEligible++;
                        }
                        break;

                    case 'Gold':
                        if ($user->upgrade_level_status === 'Completed' || $user->upgrade_level_status === 'Waiting') {
                            $goldCount++;
                        } else if ($user->upgrade_level_status === 'Pending') {
                            $goldEligible++;
                        }
                        break;

                    case 'Platinum':
                        if ($user->upgrade_level_status === 'Completed' || $user->upgrade_level_status === 'Waiting') {
                            $platinumCount++;
                        } else if ($user->upgrade_level_status === 'Pending') {
                            $platinumEligible++;
                        }
                        break;
                }
            }

            $pendingApprovalCounts = $pendingApprovalCounts ?: 0;
            $distinctUserCount = $distinctUserCount ?: 0;
            $completedUsersCount = $completedUsersCount ?: 0;
            $inProgressUsersCount = $inProgressUsersCount ?: 0;
            $silverCount = $silverCount ?: 0;
            $goldCount = $goldCount ?: 0;
            $platinumCount = $platinumCount ?: 0;
            $silverEligible = $silverEligible ?: 0;
            $goldEligible = $goldEligible ?: 0;
            $platinumEligible = $platinumEligible ?: 0;

            // dd(
            //     $Courses,
            //     $role,
            //     $enrolledCourses,
            //     $courseStatuses,
            //     $pendingApprovalCounts,
            //     $distinctUserCount,
            //     $completedUsersCount,
            //     $inProgressUsersCount,
            //     $silverCount,
            //     $goldCount,
            //     $platinumCount,
            //     $silverEligible,
            //     $goldEligible,
            //     $platinumEligible
            // );

            return view('dashboard', compact('Courses', 'role', 'enrolledCourses', 'courseStatuses', 'pendingApprovalCounts', 'distinctUserCount', 'completedUsersCount', 'inProgressUsersCount', 'silverCount', 'goldCount', 'platinumCount', 'silverEligible', 'goldEligible', 'platinumEligible'));
        } catch (Exception $e) {
            return back()->with("error", "Something Went Wrong");
        }
    }

    public function enroll(Request $request)
    {
        try {
            $user = Auth::user()->empid;
            $course_id = $request->id;

            // Check if the user is already enrolled in the course
            $alreadyEnrolled = DB::table('user_progress')
                ->where('empid', $user)
                ->where('course_id', $course_id)
                ->exists();

            if ($alreadyEnrolled) {
                return response()->json(['error' => "You are already enrolled in this course.", 'success' => false]);
            }

            $modules = DB::table('module_master')
                ->where('course_id', $course_id)
                ->pluck('id');

            foreach ($modules as $module_id) {
                DB::table('user_progress')->insert([
                    'empid' => $user,
                    'course_id' => $course_id,
                    'course_status' => 'In Progress',
                    'module_id' => $module_id,
                    'module_status' => 'Pending',
                    'approval_status' => 'Pending',
                ]);
            }

            $html = view('course.msg')->render();
            return response()->json(['html' => $html, 'success' => true]);
        } catch (Exception $e) {
            return response()->json(['error' => "Something went wrong - " . $e->getMessage(), 'success' => false]);
        }
    }

    public function restart(Request $request)
    {
        try {
            $user = Auth::user()->empid;
            $module_id = $request->id;
            $html = view('course.restart', compact('module_id'))->render();
            return response()->json(['html' => $html, 'success' => true]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong', 'success' => false]);
        }
    }

    public function restartcourse(Request $request)
    {
        try {
            $user = Auth::user()->empid;
            $module_id = $request->module_id;

            // Get course_id from module_master
            $course_id = DB::table('module_master')
                ->where('id', $module_id)
                ->value('course_id');

            if (!$course_id) {
                return response()->json(['error' => 'Module not found', 'success' => false]);
            }

            // Get all module IDs for this course
            $module_ids = DB::table('module_master')
                ->where('course_id', $course_id)
                ->pluck('id')
                ->toArray();

            // Delete records from user_progress where course_id and module_id match for the user
            DB::table('user_progress')
                ->where('empid', $user)
                ->where('course_id', $course_id)
                ->whereIn('module_id', $module_ids)
                ->delete();

            // Delete records from user_watch_history where module_id matches for the user
            DB::table('user_watch_history')
                ->where('user_id', $user)
                ->whereIn('module_id', $module_ids)
                ->delete();

            // Get quiz IDs from quiz_master for the given module IDs
            $quiz_ids = DB::table('quiz_master')
                ->whereIn('module_id', $module_ids)
                ->pluck('id')
                ->toArray();

            // Delete records from user_assessment where quiz_id matches and user_id is the current user
            DB::table('user_assessment')
                ->where('user_id', $user)
                ->whereIn('quiz_id', $quiz_ids)
                ->delete();

            return response()->json(['success' => true, 'message' => 'Course Progress Restarted for the User.']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong', 'success' => false, 'message' => $e->getMessage()]);
        }
    }
}