<?php

namespace App\Http\Controllers\Admin\Approval;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Auth;

class ModuleApproveController extends Controller
{
    public function view()
    {
        try {
            $user = Auth::user();
            $WaitingDataQuery = DB::table('user_progress')
                ->join('users', 'user_progress.empid', '=', 'users.empid')
                ->join('course_master', 'user_progress.course_id', '=', 'course_master.id')
                ->join('module_master', 'user_progress.module_id', '=', 'module_master.id')
                ->select(
                    'user_progress.empid',
                    'users.name as employee_name',
                    'user_progress.course_id',
                    'course_master.title as course_name',
                    'user_progress.module_id',
                    'module_master.title as module_name',
                    'user_progress.module_status',
                    'user_progress.submit_for_approval',
                    'user_progress.approval_status',
                    DB::raw("CASE 
                    WHEN user_progress.module_status = 'Waiting' AND user_progress.approval_status = 'Waiting' THEN 'Re-Attempt'
                    WHEN user_progress.module_status = 'In Progress' AND user_progress.approval_status = 'Waiting' THEN 'First Attempt'
                    ELSE 'N/A' 
                END as acceptance_status")
                )
                ->where(function ($query) {
                    $query->where('user_progress.module_status', 'In Progress')
                        ->where('user_progress.approval_status', 'Waiting');
                })
                ->orWhere(function ($query) {
                    $query->where('user_progress.module_status', 'Waiting')
                        ->where('user_progress.approval_status', 'Waiting');
                });

            // Role-based filter
            if ($user->role === 'L3') {
                $WaitingDataQuery->whereIn('users.role', ['L2', 'L1']);
            }

            $WaitingData = $WaitingDataQuery->get();

            return view('admin.approval.module', compact('WaitingData'));
        } catch (Exception $e) {
            return back()->with("error", "Something Went Wrong: " . $e->getMessage());
        }
    }

    public function accept(Request $request)
    {
        try {
            $moduleId = $request->module_id;
            $empid = $request->empid;
            $userId = Auth::user()->empid;

            $updated = DB::table('user_progress')
                ->where('module_id', $moduleId)
                ->where('empid', $empid)
                ->update([
                    'course_status' => 'Completed',
                    'module_status' => 'Completed',
                    'approval_status' => 'Approved',
                    'approved_by' => $userId,
                    'completed_on' => now(),
                ]);

            if ($updated) {
                return response()->json(['success' => true, 'message' => 'Request Accepted Successfully']);
            } else {
                return response()->json(['success' => false, 'message' => 'No matching record found'], 404);
            }
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong'], 500);
        }
    }

    public function reject(Request $request)
    {
        try {
            $moduleId = $request->module_id;
            $empid = $request->empid;
            $userId = Auth::user()->empid;

            $updated = DB::table('user_progress')
                ->where('module_id', $moduleId)
                ->where('empid', $empid)
                ->update([
                    'approval_status' => 'Declined',
                    'approved_by' => $userId,
                ]);

            if ($updated) {
                return response()->json(['success' => true, 'message' => 'Request Rejected Successfully']);
            } else {
                return response()->json(['success' => false, 'message' => 'No matching record found'], 404);
            }
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong'], 500);
        }
    }
}