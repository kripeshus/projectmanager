<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TimeEntryController;

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

Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/signup', [AuthController::class, 'showSignUpForm'])->name('login');
Route::post('/signup', [AuthController::class, 'signup'])->name('signup.submit');
Route::get('/task', [TaskController::class, 'index'])->middleware('auth')->name('task');
Route::get('/project', [ProjectController::class, 'index'])->middleware('auth')->name('project');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::get('/time', [TimeEntryController::class, 'index'])->middleware('auth')->name('time');
Route::get('/search/project', [ProjectController::class, 'search'])->name('project.search');
Route::get('/search/task', [TaskController::class, 'search'])->name('task.search');
Route::post('/time', [TimeEntryController::class, 'time'])->middleware('auth')->name('timeentry.add');
Route::get('/report', [TimeEntryController::class, 'report'])->middleware('auth')->name('timeentry.report');
Route::get('/report/data', [TimeEntryController::class, 'getReport'])->middleware('auth')->name('timeentry.data');