<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\SemesterController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\EnsureTokenIsValid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware([EnsureTokenIsValid::class])->group(function () {
    Route::post('/all/checknotes', [NoteController::class, 'checknotes']);
    Route::post('/all/update', [AuthController::class, 'update']);
    Route::get('/all/checkuser', [AuthController::class, 'checkuser']);
    Route::resource('/admin/user', UserController::class);
    Route::post('/admin/user/delete', [UserController::class, 'delete']);
    Route::post('/admin/user/update/status', [UserController::class, 'updateStatus']);


    Route::resource('/admin/role', RoleController::class);
    Route::post('/admin/role/delete', [RoleController::class, 'delete']);
    Route::post('/admin/role/update/status', [RoleController::class, 'updateStatus']);

    Route::resource('/admin/semester', SemesterController::class);
    Route::post('/admin/semester/delete', [SemesterController::class, 'delete']);
    Route::post('/admin/semester/update/status', [SemesterController::class, 'updateStatus']);


    Route::get('/students', [UserController::class, 'getStudents']);
    Route::resource('/notes', NoteController::class);
    Route::get('/admin/lecturers/students', [UserController::class, 'getStudents']);
    Route::resource('/admin/lecturers/notes', NoteController::class);
    Route::get('/admin/lecturers/semester', [SemesterController::class, 'index']);
});
