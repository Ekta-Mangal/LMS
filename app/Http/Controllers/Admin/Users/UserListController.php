<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\DB;

class UserListController extends Controller
{
    public function view()
    {
        try {
            $users = DB::table('users')
                ->select(
                    'name',
                    'empid',
                    'email',
                    'location',
                    'designation',
                    'role',
                    'client_name',
                    'process',
                    'subprocess',
                    'reporting_manager',
                    'badge_level'
                )
                ->get();

            return view('admin.user_list.view', compact('users'));
        } catch (Exception $e) {
            return back()->with("error", "Something Went Wrong");
        }
    }
}