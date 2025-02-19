<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsAdmin;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// 従業員側
Route::middleware(['auth'])->prefix('attendance')->group(function () {
    Route::get('/', [App\Http\Controllers\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/', [App\Http\Controllers\AttendanceController::class, 'store'])->name('attendance.store');
    Route::put('/{id}', [App\Http\Controllers\AttendanceController::class, 'update'])->name('attendance.update');
    Route::post('/attendance/request', [App\Http\Controllers\AttendanceRequestController::class, 'store'])->name('attendance.request');
});

Auth::routes();

Route::middleware(IsAdmin::class)->group(function () {
    Route::get('/users', [App\Http\Controllers\AdminController::class, 'index'])->name('users.index');
    Route::get('/users/attendanceRequests', [App\Http\Controllers\AttendanceRequestController::class, 'showRequests'])->name('users.attendanceRrequests');
    Route::get('/users/attendanceRequests/{id}/{status}', [App\Http\Controllers\AttendanceRequestController::class, 'updateStatus'])->name('attendance.request.update');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
