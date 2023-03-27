<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SemesterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response(Semester::get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response([
                    'errors' => $validator->errors()->all(), 'message' => json_encode($validator->errors()->all()),
                    'icon' => 'error'
                ], 422);
            }

            if (isset($request->id)) {

                $user = new Semester();
                $user->name = $request->get('name');
                $user->update();
                
            } else {

                $user = new Semester();
                $user->name = $request->get('name');
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
            Semester::where('id', $request->id)->update(['status' => !$request->status]);
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
            Semester::where('id', $request->id)->delete();
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
