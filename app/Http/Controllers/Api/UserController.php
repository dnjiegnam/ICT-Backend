<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response(User::with('roles')->get(), 200);
    }

    
    /**
     * Display a listing of the resource.
     */
    public function getStudents()
    {
        return response(User::with('notes')->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            if (isset($request->id)) {

                $user = User::where('id', $request->id)->first();
                $user->name = $request->get('name');
                $user->email = $request->get('email');
                $user->role_id = $request->get('role_id');
                if (isset($request->password)) {
                    $user->password = Hash::make($request->get('password'));
                }
                $user->update();

            } else {

                $validator = Validator::make($request->all(), [
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|max:255|email:rfc,dns|unique:users',
                    'password' => 'required|string|min:6',
                    'role_id' => 'required|string',
                ]);

                if ($validator->fails()) {
                    return response([
                        'errors' => $validator->errors()->all(), 'message' => json_encode($validator->errors()->all()),
                        'icon' => 'error'
                    ], 422);
                }

                $user = new User();
                $user->name = $request->get('name');
                $user->email = $request->get('email');
                $user->role_id = $request->get('role_id');
                $user->password = Hash::make($request->get('password'));
                if (isset($request->matricule)) {
                    $user->matricule = $request->matricule;
                }
                $user->remember_token  = \Str::random(10);
                $user->save();
            }

            return response([
                'status' => 'success',
                'message' => 'Done with success!', 'icon' => 'success',
                'data' => $user
            ], 200);
        } catch (\Throwable $th) {
            return response([
                'status' => 'error',
                'error' => 'invalid.credentials',
                'message' => 'Oops something when wrong please contact the administrator. (' . $th->getMessage() . ')',
                'icon' => 'error'
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * updateStatus the specified resource in storage.
     */
    public function updateStatus(Request $request)
    {
        try {
            User::where('id', $request->id)->update(['satus' => !$request->status]);
            return response(['message' => 'Updated with success', 'icon' => 'success'], 200);
        } catch (\Throwable $th) {
            return response([
                'status' => 'error',
                'error' => 'invalid.credentials',
                'message' => 'Oops something when wrong please contact the administrator. (' . $th->getMessage() . ')',
                'icon' => 'error'
            ], 400);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {
        try {
            User::where('id', $request->id)->delete();
            return response(['message' => 'Deleted with success', 'icon' => 'success'], 200);
        } catch (\Throwable $th) {
            return response([
                'status' => 'error',
                'error' => 'invalid.credentials',
                'message' => 'Oops something when wrong please contact the administrator. (' . $th->getMessage() . ')',
                'icon' => 'error'
            ], 400);
        }
    }
}
