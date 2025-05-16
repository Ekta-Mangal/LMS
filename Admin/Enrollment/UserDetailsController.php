<?php

namespace App\Http\Controllers\Admin\Enrollment;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Exception;

class UserDetailsController extends Controller
{
    public function view()
    {
        try {
            $users = DB::table('users')
                ->leftJoin('user_progress', function ($join) {
                    $join->on(
                        DB::raw("user_progress.empid COLLATE utf8mb4_unicode_ci"),
                        '=',
                        DB::raw("users.empid COLLATE utf8mb4_unicode_ci")
                    );
                })
                ->join('course_master', 'user_progress.course_id', '=', 'course_master.id')
                ->leftJoin('quiz_master', 'quiz_master.module_id', '=', 'user_progress.module_id')
                ->leftJoin('user_assessment', function ($join) {
                    $join->on(
                        DB::raw("user_assessment.quiz_id COLLATE utf8mb4_unicode_ci"),
                        '=',
                        DB::raw("quiz_master.id COLLATE utf8mb4_unicode_ci")
                    )
                        ->on(
                            DB::raw("user_assessment.user_id COLLATE utf8mb4_unicode_ci"),
                            '=',
                            DB::raw("users.empid COLLATE utf8mb4_unicode_ci")
                        );
                })
                ->select(
                    'users.name',
                    'users.empid',
                    'users.location',
                    'users.designation',
                    'users.role',
                    'users.process',
                    'users.subprocess',
                    'users.reporting_manager',
                    'users.badge_level',
                    'user_progress.course_id',
                    'user_progress.created_at',
                    'user_progress.course_status',
                    'user_progress.completed_on',
                    'course_master.title as course_title',
                    'quiz_master.id as quiz_id',
                    'quiz_master.quiz_title',
                    'user_assessment.score',
                    'user_assessment.status',
                    'user_assessment.created_on'
                )
                ->get();

            return view('admin.enrollment.view', compact('users'));
        } catch (Exception $e) {
            return back()->with("error", "Something Went Wrong");
        }
    }
}