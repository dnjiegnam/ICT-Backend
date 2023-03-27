<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            Note::updateOrInsert([
                'lecturer_id' => $request->lecturer_id,
                'student_id' => $request->student_id,
                'semester_id' => $request->semester_id,
            ], [
                'lecturer_id' => $request->lecturer_id,
                'student_id' => $request->student_id,
                'semester_id' => $request->semester_id,
                'note' => $request->note
            ]);
            return response([
                'status' => 'success',
                'message' => 'Done with success!', 'icon' => 'success',
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function checknotes(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'matricule' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response([
                    'errors' => $validator->errors()->all(), 'message' => json_encode($validator->errors()->all()),
                    'icon' => 'error'
                ], 422);
            }

            if ($user = User::where('matricule', $request->matricule)->where('satus', 1)->first()) {
                $notes = Note::with('lecturer', 'semester')->where('student_id', $user->id)->get();
                return response(['notes' => $notes], 200);
            }
            return response([
                'message' => 'Student not found or Disabled',
                'icon' => 'error'
            ], 404);
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
