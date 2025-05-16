<?php

namespace App\Http\Controllers\Admin\ManageCourse;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ManageModuleController extends Controller
{
    public function list()
    {
        try {
            $Modules = DB::table('module_master')
                ->leftJoin('users', DB::raw("BINARY module_master.created_by"), '=', DB::raw("BINARY users.empid"))
                ->leftJoin('course_master', DB::raw("BINARY module_master.course_id"), '=', DB::raw("BINARY course_master.id"))
                ->select(
                    'module_master.*',
                    'course_master.title as course_name',
                    'course_master.module_count as module_count',
                    'users.name as created_by_name'
                )
                ->get();

            // Fetch prerequisite module titles
            foreach ($Modules as $module) {
                if (!empty($module->prerequisite_module)) {
                    $moduleIds = explode(',', $module->prerequisite_module);
                    $moduleTitles = DB::table('module_master')
                        ->whereIn('id', $moduleIds)
                        ->pluck('title')
                        ->toArray();
                    $module->prerequisite_module_titles = implode(', ', $moduleTitles);
                } else {
                    $module->prerequisite_module_titles = 'N/A';
                }
            }

            return view('admin.managemodule.list', compact('Modules'));
        } catch (Exception $e) {
            return back()->with("error", "Something Went Wrong: " . $e->getMessage());
        }
    }

    public function add()
    {
        try {
            $courses = DB::table('course_master')->select('id', 'title')->distinct()->get();
            $html = view('admin.managemodule.add', compact('courses'))->render();
            return response()->json(['html' => $html, 'success' => true]);
        } catch (Exception $e) {
            return back()->with("error", "Something Went Wrong");
        }
    }

    public function getModulesByCourse(Request $request)
    {
        try {
            $modules = DB::table('module_master')
                ->select('id', 'title')
                ->where('course_id', $request->course_id)
                ->distinct()
                ->get();

            return response()->json(['modules' => $modules, 'success' => true]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    public function postadd(Request $request)
    {
        try {
            $userId = Auth::user()->empid;
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'course_id' => 'required',
                'prerequisite_required' => 'required',
                'module_id' => 'required_if:prerequisite_required,Yes|array',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $module = new Module();
            $module->title = $request->title;
            $module->course_id = $request->course_id;
            $module->prerequisite_required = $request->prerequisite_required;

            // Store module IDs as a comma-separated string in `prerequisite_module`
            $module->prerequisite_module = ($request->prerequisite_required == 'Yes')
                ? implode(',', $request->module_id)
                : null;

            $module->created_by = $userId;
            $module->save();

            return back()->with('success', 'Module Created Successfully');
        } catch (Exception $e) {
            return back()->with("error", "Something Went Wrong: " . $e->getMessage());
        }
    }

    public function edit(Request $request)
    {
        try {
            $id = $request->id;
            $module = Module::find($id);
            $courses = DB::table('course_master')->select('id', 'title')->distinct()->get();
            $html = view('admin.managemodule.edit', compact('module', 'courses'))->render();
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
                'course_id' => 'required',
                'prerequisite_required' => 'required',
                'module_id' => 'required_if:prerequisite_required,Yes|array',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $module = Module::find($request->id);
            $module->title = $request->title;
            $module->course_id = $request->course_id;
            $module->prerequisite_required = $request->prerequisite_required;
            // Store module IDs as a comma-separated string in `prerequisite_module`
            $module->prerequisite_module = ($request->prerequisite_required == 'Yes')
                ? implode(',', $request->module_id)
                : null;
            $module->updated_by = $userId;
            $module->update();
            return back()->with('success', 'Module Updated Successfully');
        } catch (Exception $e) {
            return back()->with("error", "Something Went Wrong");
        }
    }

    public function delete(Request $request)
    {
        try {
            $inProgress = DB::table('user_progress')
                ->where('module_id', $request->id)
                ->where('module_status', 'In progress')
                ->exists();

            if ($inProgress) {
                return response()->json(['message' => 'Module cannot be deleted as it is in progress', 'status' => 'error']);
            }

            $delete = Module::where('id', $request->id)->delete();
            if ($delete) {
                return response()->json(['message' => 'Module Deleted Successfully', 'status' => 'success']);
            }

            return response()->json(['message' => 'Module Delete Failed', 'status' => 'error']);
        } catch (Exception $e) {
            return back()->with("error", "Something Went Wrong");
        }
    }
}