<?php

namespace App\Http\Controllers\Certificate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use App\Models\Module;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;

class CertifiController extends Controller
{
    public function view()
    {
        try {
            $userId = Auth::user()->empid;
            $courses = Course::select('id', 'title')->get();

            $achievements = [];

            foreach ($courses as $course) {
                $moduleIds = Module::where('course_id', $course->id)->pluck('id');

                // Check if all modules have 'Completed' status for the user
                $completedModulesCount = DB::table('user_progress')
                    ->whereIn('module_id', $moduleIds)
                    ->where('empid', $userId)
                    ->where('module_status', 'Completed')
                    ->where('course_status', 'Completed')
                    ->count();

                // If all modules in the course are completed, allow download
                $isCompleted = $completedModulesCount == count($moduleIds) && count($moduleIds) > 0;

                $achievements[] = [
                    'course_title' => $course->title,
                    'is_completed' => $isCompleted
                ];
            }

            return view('certificate.view', compact('achievements'));
        } catch (Exception $e) {
            return back()->with("error", "Something Went Wrong");
        }
    }

    public function download(Request $request)
    {
        try {
            $user = Auth::user()->name;
            $userid = Auth::user()->empid;
            $course_name = $request->course;

            // Get course_id and badge from course_master
            $course = DB::table('course_master')
                ->where('title', $course_name)
                ->select('id', 'badge')
                ->first();

            if (!$course) {
                return back()->with("error", "Course Details not found for the selected course.");
            }

            // Get issue_date from user_progress
            $issue = DB::table('user_progress')
                ->where('empid', $userid)
                ->where('course_id', $course->id)
                ->where('approval_status', "Approved")
                ->where('approved_by', "Server")
                ->select('issue_date')
                ->first();

            // Determine issue date
            if ($issue && $issue->issue_date) {
                $issueDate = \Carbon\Carbon::parse($issue->issue_date);
            } else {
                $issueDate = now();

                // Update DB with current issue date
                DB::table('user_progress')
                    ->where('empid', $userid)
                    ->where('course_id', $course->id)
                    ->where('approval_status', "Approved")
                    ->where('approved_by', "Server")
                    ->update(['issue_date' => $issueDate->format('Y-m-d')]);
            }

            // Format issue date like "April 11th, 2025"
            $day = $issueDate->day;
            $suffix = match (true) {
                $day % 100 >= 11 && $day % 100 <= 13 => 'th',
                $day % 10 == 1 => 'st',
                $day % 10 == 2 => 'nd',
                $day % 10 == 3 => 'rd',
                default => 'th',
            };
            $formattedIssueDate = $issueDate->format("F") . " " . $day . $suffix . ", " . $issueDate->format("Y");

            // Generate PDF
            $pdf = Pdf::loadView('certificate.layout', [
                'course_name' => $course_name,
                'employee_name' => $user,
                'issue_date' => $formattedIssueDate,
                'badge_level' => $course->badge
            ])->setPaper('A4', 'landscape');


            // return $pdf->stream("{$user}_Certificate.pdf");
            return $pdf->download("{$user}_Certificate.pdf");
        } catch (\Exception $e) {
            return back()->with("error", "Something went wrong while generating the certificate.");
        }
    }

    public function layout()
    {
        try {
            return view('certificate.layout1');
        } catch (Exception $e) {
            return back()->with("error", "Something Went Wrong");
        }
    }
}