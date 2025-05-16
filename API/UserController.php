<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function store(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input, [
                'employees' => 'required|array|min:1',
                'employees.*.name' => 'required|string|max:100',
                'employees.*.empid' => 'required|string|unique:users,empid',
                'employees.*.email' => 'required|email|unique:users,email',
                'employees.*.location' => 'nullable|string|max:100',
                'employees.*.designation' => 'required|string|max:100',
                'employees.*.client_name' => 'nullable|string|max:100',
                'employees.*.process' => 'nullable|string|max:100',
                'employees.*.subprocess' => 'nullable|string|max:100',
                'employees.*.reporting_manager' => 'nullable|string|max:100',
                'employees.*.profile_photo' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()
                ], 400);
            }

            $created = [];

            foreach ($input['employees'] as $emp) {
                // Define role & badge_level based on designation
                $designation = $emp['designation'];
                $role = null;
                $badge = null;

                if (in_array($designation, [
                    'Assistant Team Leader',
                    'Executive',
                    'Group Team Leader',
                    'Project Assistant Team Leader',
                    'Project Executive',
                    'Senior Executive',
                    'Team Leader'
                ])) {
                    $role = 'L1';
                    $badge = 'Silver';
                } elseif (in_array($designation, ['Assistant Manager', 'Manager'])) {
                    $role = 'L2';
                    $badge = 'Gold';
                } elseif (in_array($designation, [
                    'Assistant Vice President',
                    'Deputy General Manager',
                    'General Manager',
                    'Senior Manager'
                ])) {
                    $role = 'L3';
                    $badge = 'Platinum';
                }

                $empData = [
                    'name' => $emp['name'],
                    'empid' => $emp['empid'],
                    'email' => $emp['email'],
                    'location' => $emp['location'] ?? null,
                    'designation' => $designation,
                    'role' => $role,
                    'client_name' => $emp['client_name'] ?? null,
                    'process' => $emp['process'] ?? null,
                    'subprocess' => $emp['subprocess'] ?? null,
                    'reporting_manager' => $emp['reporting_manager'] ?? null,
                    'badge_level' => $badge,
                    'profile_photo' => $emp['profile_photo'] ?? null,
                    'password' => Hash::make('#Asgu@rd')
                ];

                $created[] = User::create($empData);
            }

            return response()->json([
                'status' => true,
                'message' => 'Employees inserted successfully.',
                'data' => $created
            ], 200);
        } catch (Exception $ex) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong with payload.',
                'error' => $ex->getMessage()
            ], 400);
        }
    }

    public function emslogin(Request $request)
    {
        try {
            $postFields = array(
                "EmployeeID" => "$request->employee_id",
                "token" => "$request->token",
                "appkey" => "checkauth",
                "url" => ""
            );

            $apiUrl = "http://172.104.207.201/erpm/Services/checkAuthentication.php";
            $response = Http::post($apiUrl, $postFields);
            $res = json_decode($response->body());

            if ($res->msg == 'Token valid') {
                $username = $request->employee_id;
                $count = User::where('empid', $username)->get()->toArray();
                if (sizeof($count) > 0) {
                    $uid = $count[0]['id'];
                    Auth::logout();
                    Auth::loginUsingId($uid);

                    $notification = array(
                        'message' => 'Login Successfully',
                        'alert-type' => 'success'
                    );
                    return redirect()->route('dashboard')->with($notification);
                } else {

                    redirect('/login');
                }
            } else {
                $message = "User not found!";
                $notification = array(
                    'message' => $message,
                    'alert-type' => 'error'
                );
                return back()->with($notification);
            }
        } catch (\Exception $ex) {
            $message = $ex->getMessage();
            $message = "Something went wrong!";
            $notification = array(
                'message' => $ex->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }
}