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
            $course_name = $request->course;
            $badge = DB::table('course_master')
                ->where('title', $course_name)
                ->value('badge');

            if (!$badge) {
                return back()->with("error", "Badge level not found for the selected course.");
            }

            // Generate PDF from Blade template
            $pdf = Pdf::loadView('certificate.layout', [
                'course_name' => $course_name,
                'employee_name' => $user,
                'badge_level' => $badge
            ])->setPaper('A4', 'landscape');
            // dd($pdf);

            return $pdf->download("{$user}_Certificate.pdf");
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return back()->with("error", "Something went wrong while generating the certificate.");
        }
    }

    public function layout(Request $request)
    {
        try {
            return view('certificate.layout');
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return back()->with("error", "Something went wrong while generating the certificate.");
        }
    }
}