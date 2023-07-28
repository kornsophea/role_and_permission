<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Validator;
class UserController extends Controller
{
    public function sendResponse($result, $message)
    {
        $response = [
            'code' => 200,
            'success' => true,
            'message' => $message,
            'data'    => $result,

        ];
        return response()->json($response, 200);
    }
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'code' => 404,
            'success' => false,
            'message' => $error,
            'data' => null,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
    public function show()
    {
        $users = User::with('permissions')->get();
        return response(['user' => $users]);
    }
    public function register(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email'    => 'unique:users|required',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ];
        $input     = $request->only('name', 'email', 'password', 'c_password');
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return $this->sendError("Resgister Fail.", $validator->messages());
        }
        $name = $request->name;
        $email    = $request->email;
        $password = $request->password;
        $user_role = Role::where(['name' => 'user'])->first();
        $role_id = $user_role->id;

        $user     = User::create(['name' => $name, 'email' => $email, 'role_id' => $role_id, 'password' => Hash::make($password), 'role_id' => $user_role->id]);
        if ($user_role) {
            $user->assignRole($user_role);
        }


        return $this->sendResponse($user, "User register successfully");
    }
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'

        ]);

        $user = User::where('email', $request['email'])->first();
        if (!isset($user)) {
            return $this->sendError('User does not exist with this details');
        }
        if (!Hash::check($request['password'], $user['password'])) {
            return $this->sendError('Incorrect user credentials');
        }

        $role_name = Role::where('id', $user->role_id)->pluck('name');
        $user?->role($role_name)->get();
        $user['token'] = $user->createToken('AuthToken')->accessToken;

        return $this->sendResponse($user, "User login Successfully");
    }
    public function index()
    {
        if (Auth::check()) {
            
            $user=Auth::user();
            $success['role']=$user->getRoleNames();
            $success['permission']=$user->getAllPermissions();
            
            return $this->sendResponse($success,"Successfully");
        }
    }
}
