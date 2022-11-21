<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PeriodController;
use App\Http\Controllers\Admin\SessionController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Artisan;
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

Route::get('clear-cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    Artisan::call('config:clear');
    Artisan::call('config:clear');
    return 'DONE'; //Return anything
});

Route::controller(UserController::class)->middleware('auth')->group(function (){
    Route::get('dashboard', 'dashboard')->name('dashboard');
    Route::get('profile', 'profile')->name('profile');

    
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->middleware(['role:admin'])->group(function(){

    //Periods
    Route::resource('periods', PeriodController::class, ['except' => ['show']]);

    // UserController
    Route::controller(UserController::class)->group(function(){
        Route::put('users/activate/{user}',  'activateUser')->name('users.activate');
        Route::put('users/deactivate/{user}', 'deactivateUser')->name('users.deactivate');
        Route::get('get-users', 'getUsers')->name('users.get');
        Route::resource('users', UserController::class);
    });
    
    //SessionController
    Route::controller(SessionController::class)->group(function(){
        Route::put('sessions/activate/{session}', 'activateSession')->name('sessions.activate');
        Route::put('sessions/deactivate/{session}', 'deactivateSession')->name('sessions.deactivate');
        Route::resource('sessions', SessionController::class);
    });

    //AdminController
    Route::controller(AdminController::class)->group(function(){
        Route::get('messages', 'messages')->name('messages.index');
        Route::get('logs', 'logs')->name('logs.index');
        Route::get('get-logs', 'getLogs')->name('get-logs');
        Route::post('get-sub-counties',  'fetchSubCounties')->name('get.subcounties');
      
    });
    
    //SettingsController
    Route::controller(SettingsController::class)->group(function (){

        // School details
        Route::get('school-details', 'schoolDetails')->name('school-details');
        Route::post('school-details', 'saveSchoolDetails')->name('save-school');

        //Default gradings
        Route::post('default-gradings/save', 'saveDefaultGrading')->name('default-gradings.save');
        Route::get('default-gradings', 'defaultGradings')->name('default-gradings.index');
    });

    
});

Route::middleware(['auth'])->prefix('account')->name('account.')->group(function(){
    Route::put('password', [UserController::class, 'passwordChange'])->name('password.change');
    Route::get('password', [UserController::class, 'password'])->name('password');
});