<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ProfileController extends Controller
{
    public function view()
    {
        try {
            $user = Auth::user();

            $achieve = DB::table('user_progress')
                ->join('course_master', 'user_progress.course_id', '=', 'course_master.id')
                ->join('module_master', 'user_progress.module_id', '=', 'module_master.id')
                ->where('user_progress.empid', $user->empid)
                ->select(
                    'user_progress.*',
                    'course_master.title as course_title',
                    'module_master.title as module_title'
                )
                ->get();
            return view('profile.view', compact('user', 'achieve'));
        } catch (Exception $e) {
            return back()->with("error", "Something Went Wrong");
        }
    }

    public function update(Request $request)
    {
        try {
            $user = User::find($request->id_edit);

            if ($request->password != null) {
                $user->password = Hash::make($request->password);
            }

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                $file = $request->file('profile_photo');
                $timestamp = now()->format('YmdHis'); // YYYYMMDDHHMMSS format
                $filename = "{$request->empid}_{$timestamp}." . $file->getClientOriginalExtension();

                // Move file to uploads/profile
                $file->move(public_path('uploads/profile'), $filename);

                // Delete old photo
                if ($user->profile_photo && file_exists(public_path($user->profile_photo))) {
                    unlink(public_path($user->profile_photo));
                }

                $user->profile_photo = $filename;
            }
            $user->update();

            return back()->with('success', 'User Profile Updated Successfully');
        } catch (Exception $e) {
            $message = $e->getMessage();
            return back()->with('error', "Something Went Wrong -> $message");
        }
    }
}