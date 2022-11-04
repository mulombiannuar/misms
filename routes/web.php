<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::controller(UserController::class)->middleware('auth')->group(function (){
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function(){
    Route::get('messages', [AdminController::class, 'messages'])->name('messages.index')->middleware(['role:admin']);
    Route::get('logs', [AdminController::class, 'logs'])->name('logs.index')->middleware(['role:admin']);
});

Route::middleware(['auth'])->prefix('account')->name('account.')->group(function(){
    Route::put('password', [UserController::class, 'passwordChange'])->name('password.change');
    Route::get('password', [UserController::class, 'password'])->name('password');
});