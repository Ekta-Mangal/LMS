<?php

namespace App\Http\Controllers\Admin\ManageCourse;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ManageCourseController extends Controller
{
    public function list()
    {
        try {
            $Courses = DB::table('course_master')
                ->leftJoin('users', 'course_master.created_by', '=', 'users.empid')
                ->select('course_master.*', 'users.name as created_by_name')
                ->get();

            return view('admin.managecourse.list', compact('Courses'));
        } catch (Exception $e) {
            return back()->with("error", "Something Went Wrong");
        }
    }

    public function add()
    {
        try {
            $html = view('admin.managecourse.add')->render();
            return response()->json(['html' => $html, 'success' => true]);
        } catch (Exception $e) {
            return back()->with("error", "Something Went Wrong");
        }
    }

    public function postadd(Request $request)
    {
        try {
            $userId = Auth::user()->empid;
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'module_count' => 'required|integer|between:1,9',
                'level' => 'required',
                'publish_date' => 'required',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            if ($request->level === 'All') {
                // Create courses for all levels
                $levels = ['L1' => 'Silver', 'L2' => 'Gold', 'L3' => 'Platinum'];

                foreach ($levels as $level => $badge) {
                    $course = new Course;
                    $course->title = $request->title;
                    $course->module_count = $request->module_count;
                    $course->level = $level;
                    $course->badge = $badge;
                    $course->publish_date = $request->publish_date;
                    $course->created_by = $userId;
                    $course->save();
                }
            } else {
                // Determine badge based on level
                if ($request->level === 'L1') {
                    $badge = 'Silver';
                } elseif ($request->level === 'L2') {
                    $badge = 'Gold';
                } elseif ($request->level === 'L3') {
                    $badge = 'Platinum';
                } else {
                    $badge = 'Unknown';
                }

                // Save course details
                $course = new Course;
                $course->title = $request->title;
                $course->module_count = $request->module_count;
                $course->level = $request->level;
                $course->badge = $badge;
                $course->publish_date = $request->publish_date;
                $course->created_by = $userId;
                $course->save();
            }

            return back()->with('success', 'Course Created Successfully');
        } catch (\Exception $e) {
            return back()->with("error", "Something Went Wrong: " . $e->getMessage());
        }
    }

    public function edit(Request $request)
    {
        try {
            $id = $request->id;
            $course = Course::find($id);
            $html = view('admin.managecourse.edit', compact('course'))->render();
            return response()->json(['html' => $html, 'success' => true]);
        } catch (Exception $e) {
            return back()->with("error", "Something Went Wrong");
        }
    }

    public function update(Request $request)
    {
        try {
            $userId = Auth::user()->empid;
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'module_count' => 'required',
                'level' => 'required',
                'publish_date' => 'required'
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $course = Course::find($request->course_id);
            $course->title = $request->title;
            $course->module_count = $request->module_count;
            $course->level = $request->level;
            if ($request->level === 'L1') {
                $course->badge = 'Silver';
            } elseif ($request->level === 'L2') {
                $course->badge = 'Gold';
            } elseif ($request->level === 'L3') {
                $course->badge = 'Platinum';
            } else {
                $course->badge = 'Unknown';
            }
            $course->publish_date = $request->publish_date;
            $course->updated_by = $userId;
            $course->update();
            return back()->with('success', 'Course Updated Successfully');
        } catch (Exception $e) {
            return back()->with("error", "Something Went Wrong");
        }
    }

    public function delete(Request $request)
    {
        try {
            $inProgress = DB::table('user_progress')
                ->where('course_id', $request->id)
                ->where('course_status', 'In progress')
                ->exists();

            if ($inProgress) {
                return response()->json(['message' => 'Course cannot be deleted as it is in progress', 'status' => 'error']);
            }

            // Delete modules associated with the course
            DB::table('module_master')->where('course_id', $request->id)->delete();

            $delete = Course::where('id', $request->id)->delete();
            if ($delete) {
                return response()->json(['message' => 'Course Deleted Successfully', 'status' => 'success']);
            }

            return response()->json(['message' => 'Course Delete Failed', 'status' => 'error']);
        } catch (Exception $e) {
            return back()->with("error", "Something Went Wrong");
        }
    }
}