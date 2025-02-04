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

// Route::prefix('items')->group(function () {
//     Route::get('/', [App\Http\Controllers\ItemController::class, 'index']);
//     Route::get('/add', [App\Http\Controllers\ItemController::class, 'add']);
//     Route::post('/add', [App\Http\Controllers\ItemController::class, 'add']);

Route::middleware(['auth'])->prefix('attendance')->group(function () {
    Route::get('/', [App\Http\Controllers\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/', [App\Http\Controllers\AttendanceController::class, 'store'])->name('attendance.store');
    Route::put('/{id}', [App\Http\Controllers\AttendanceController::class, 'update'])->name('attendance.update');
});

Auth::routes();

Route::middleware(IsAdmin::class)->group(function () {
    Route::get('/users', [App\Http\Controllers\AdminController::class, 'index'])->name('users.index');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
