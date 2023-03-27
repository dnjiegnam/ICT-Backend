<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|email:rfc,dns|unique:users',
            'password' => 'required|string|min:6',
            'confirm_password' => 'required|string|min:6|same:password',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $user = new User();
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = Hash::make($request->get('password'));
        $user->remember_token  = \Str::random(10);
        $user->save();

        return response([
            'status' => 'success',
            'data' => $user
        ], 200);
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'login' => 'required|string|max:255',
                'password' => 'required|string|min:4',
            ]);
            if ($validator->fails()) {
                return response([
                    'errors' => $validator->errors()->all(), 'message' => json_encode($validator->errors()->all()),
                    'icon' => 'error'
                ], 422);
            }

            if (!$user = User::with('roles')->where('name', $request->login)->first()) {
                return response([
                    'status' => 'error',
                    'error' => 'invalid.credentials',
                    'message' => 'Invalid Login',
                    'icon' => 'error'
                ], 400);
            }
            
            if (!Hash::check($request->password, $user->password)) {
                return response([
                    'status' => 'error',
                    'error' => 'invalid.credentials',
                    'message' => 'Invalid Password',
                    'icon' => 'error'
                ], 400);
            }

            $token = $user->createToken($request->login);

            return response(['token' => $token->plainTextToken, 'role' => $user->roles->name, 'message' => 'Logged with success!', 'icon' => 'success'], 200);
        } catch (\Throwable $th) {
            return response([
                'status' => 'error',
                'error' => 'invalid.credentials',
                'message' => 'Oops something when wrong please contact the administrator.',
                'icon' => 'error'
            ], 400);
        }
    }


    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|max:255|email:rfc,dns',
                'old_password' => 'required|string|min:4',
                'new_password' => 'string',
                'confirm_password' => 'string|same:new_password',
            ]);

            if ($validator->fails()) {
                return response([
                    'errors' => $validator->errors()->all(), 'message' => json_encode($validator->errors()->all()),
                    'icon' => 'error'
                ], 422);
            }

            $user = User::updateOrInsert([
                'email' => $request->email,
            ], [
                'email' => $request->email,
                'name' => $request->name,
                'password' => Hash::make($request->new_password),
            ]);

            return response([
                'status' => 'success',
                'data' => $user,
                'message' => 'Done with success!', 'icon' => 'success'
            ], 200);
        } catch (\Throwable $th) {
            return response([
                'status' => 'error',
                'error' => 'invalid.credentials',
                'message' => 'Oops something when wrong please contact the administrator.',
                'icon' => 'error'
            ], 400);
        }
    }

    public function logout()
    {
        try {
            $authToken = $_SERVER['HTTP_AUTHENTICATION'];
            $token = explode(" ", $authToken)[1];
            if ($user = PersonalAccessToken::findToken($token)) {
                foreach ($user->tokenable->tokens as  $token) {
                    $token->delete();
                }
                return response([
                    'status' => 'success',
                    'message' => 'Logout with success!',
                    'icon' => 'success',
                    'logout' => '/'
                ], 200);
            }
        } catch (\Throwable $th) {
            return response([
                'status' => 'error',
                'error' => 'invalid.credentials',
                'message' => 'Oops something when wrong please contact the administrator.',
                'icon' => 'error'
            ], 400);
        }
    }

    public function checkuser()
    {
        try {
            $authToken = $_SERVER['HTTP_AUTHENTICATION'];
            $token = explode(" ", $authToken)[1];
            if ($user = PersonalAccessToken::findToken($token)) {
                return response($user, 200);
            }
            return response([], 401);
        } catch (\Throwable $th) {
            return response([
                'status' => 'error',
                'error' => 'invalid.credentials',
                'message' => 'Oops something when wrong please contact the administrator.',
                'icon' => 'error'
            ], 400);
        }
    }
}
