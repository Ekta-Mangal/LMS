<?php

namespace App\Http\Controllers\Admin\Approval;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Auth;

class LevelUpgradeController extends Controller
{
    public function view()
    {
        try {
            $user = Auth::user();

            $query = DB::table('users')
                ->select('empid', 'name', 'location', 'designation', 'role', 'client_name', 'process', 'subprocess', 'reporting_manager', 'badge_level', 'upgrade_level_status', 'submit_for_approval')
                ->where('upgrade_level_status', 'Waiting');

            // Apply role-based filtering
            if ($user->role === 'L3') {
                $query->whereIn('role', ['L2', 'L1']);
            }

            $users = $query->get();
            if ($users->isEmpty()) {
                $user = "";
            }

            return view('admin.approval.upgrade', compact('users'));
        } catch (\Exception $e) {
            return back()->with("error", "Something went wrong: " . $e->getMessage());
        }
    }

    public function viewdetails(Request $request)
    {
        try {
            $empid = $request->empid;
            $userDetails = DB::table('users')->where('empid', $empid)->first();

            if (!$userDetails) {
                return back()->with("error", "User not found");
            }
            $html = view('admin.approval.details', compact('userDetails'))->render();
            return response()->json(['html' => $html, 'success' => true]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong', 'success' => false]);
        }
    }

    public function accept(Request $request)
    {
        try {
            $empid = $request->empid;
            $role = $request->role;
            $badge_level = $request->badge_level;
            $userId = Auth::user()->empid;

            // Get the current role and badge level
            $currentUser = DB::table('users')->where('empid', $empid)->first();

            if (!$currentUser) {
                return back()->with('error', 'User not found');
            }

            // Compare current values with new values
            $status = ($currentUser->role != $role || $currentUser->badge_level != $badge_level)
                ? "Pending"
                : "Completed";

            // Update user data
            DB::table('users')
                ->where('empid', $empid)
                ->update([
                    'role' => $role,
                    'badge_level' => $badge_level,
                    'processed_by' => $userId,
                    'upgrade_level_status' => $status,
                    'updated_at' => now(),
                ]);

            return back()->with('success', 'User Level Updated Successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Something Went Wrong');
        }
    }


    public function reject(Request $request)
    {
        try {
            $empid = $request->empid;
            $userId = Auth::user()->empid;

            $updated = DB::table('users')
                ->where('empid', $empid)
                ->update([
                    'upgrade_level_status' => 'Declined',
                    'processed_by' => $userId,
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