<?php

use App\Http\Controllers\Academic\ClassAttendanceController;
use App\Http\Controllers\Academic\ExamController;
use App\Http\Controllers\Academic\FormController;
use App\Http\Controllers\Academic\OverallGradingController;
use App\Http\Controllers\Academic\ScoreController;
use App\Http\Controllers\Academic\SectionController;
use App\Http\Controllers\Academic\SubjectController;
use App\Http\Controllers\Academic\SubjectGradingController;
use App\Http\Controllers\Academic\SubjectTeacherController;
use App\Http\Controllers\Academic\SubmittedScoresController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PDFController;
use App\Http\Controllers\Admin\PeriodController;
use App\Http\Controllers\Admin\SessionController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Hostel\HostelController;
use App\Http\Controllers\Hostel\RoomController;
use App\Http\Controllers\Student\ParentController;
use App\Http\Controllers\Student\StudentController;
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
    Route::post('get-teacher-sections',  'fetchTeacherSections')->name('get.teachersections');
    Route::post('get-form-sections',  'fetchFormSections')->name('get.formsections');
    Route::post('get-form-exams',  'fetchFormExams')->name('get.formexams');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function(){

    //Periods
    Route::resource('periods', PeriodController::class, ['except' => ['show']]);

    //Forms
    Route::resource('forms', FormController::class, ['except' => ['show']]);

    //Sections
    Route::resource('sections', SectionController::class, ['except' => ['show']]);

    //Subjects
    Route::resource('subjects', SubjectController::class, ['except' => ['show']]);

    //Subject teachers
    Route::resource('subject-teachers', SubjectTeacherController::class)->only(['index', 'store', 'destroy']);

    //SubjectGradingController
    Route::resource('subject-grading', SubjectGradingController::class, ['only' => ['index','store']]);

    //OverallGradingController
    Route::resource('overall-grading', OverallGradingController::class, ['only' => ['index','store']]);


    // UserController
    Route::controller(UserController::class)->group(function(){
        Route::put('users/activate/{user}',  'activateUser')->name('users.activate');
        Route::put('users/deactivate/{user}', 'deactivateUser')->name('users.deactivate');
        Route::post('users/store-role',  'storeUserRole')->name('users.storerole');
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

    //ExamController
    Route::controller(ExamController::class)->group(function(){
        Route::put('exams/activate/{exam}', 'activateExam')->name('exams.activate');
        Route::put('exams/deactivate/{exam}', 'deactivateExam')->name('exams.deactivate');
        Route::resource('exams', ExamController::class);
    });

});

Route::middleware(['auth'])->prefix('account')->name('account.')->group(function(){
    Route::put('password', [UserController::class, 'passwordChange'])->name('password.change');
    Route::get('password', [UserController::class, 'password'])->name('password');
});

Route::middleware(['auth', 'role:admin'])->prefix('hostel')->name('hostel.')->group(function(){
   
    //Hostels 
    Route::post('get-rooms', [HostelController::class, 'fetchHostelRooms'])->name('hostels.get-rooms');
    Route::resource('hostels', HostelController::class);

    //Rooms and Bed space
    Route::controller(RoomController::class)->group(function(){
        Route::post('get-spaces', 'fetchRoomsBeds')->name('rooms.fetch-spaces');
        Route::get('get-spaces', 'getBedSpaces')->name('rooms.get-spaces');
        Route::get('bed-spaces', 'bedSpaces')->name('rooms.bed-spaces');
        Route::post('save-space', 'saveBedSpace')->name('rooms.save-space');
        Route::post('assign-room', 'assignStudentRoom')->name('rooms.assign');
        Route::delete('delete-space/{id}', 'deleteBedSpace')->name('rooms.delete-space');
        Route::resource('rooms', RoomController::class, ['except' => ['edit', 'create']]);
    });
});


Route::middleware(['auth', 'role:admin'])->prefix('students')->name('students.')->group(function(){
   
    //Students
    Route::controller(StudentController::class)->group(function(){
        Route::post('students/store-subjects/',  'storeStudentSubjects')->name('store-subjects');
        Route::put('students/update-photo/{id}',  'updateStudentPhoto')->name('update-photo');
        Route::put('students/reset-password/{id}',  'resetPassword')->name('reset-password');
        Route::get('get-students',  'getStudents')->name('get.students');
        Route::resource('students', StudentController::class);
    });

    //Parents
    Route::controller(ParentController::class)->group(function(){
        Route::get('get-parents',  'getParents')->name('parents.get-parents');
        Route::post('parents/add-existing',  'addExistingParent')->name('parents.add');
        Route::resource('parents', ParentController::class);
    });
});

Route::middleware(['auth', 'role:admin'])->prefix('attendances')->name('attendances.')->group(function(){
   
    //ClassAttendanceController
    Route::controller(ClassAttendanceController::class)->group(function(){
        Route::get('class-attendances/student-report/{id}',  'studentClassAttendanceReport')->name('class-attendances.student-report');
        Route::put('class-attendances/update-attendance/{id}',  'updateStudentClassAttendance')->name('class-attendances.update-attendance');
        Route::post('class-attendances/save',  'storeStudentsClassAttendance')->name('class-attendances.save');
        Route::resource('class-attendances', ClassAttendanceController::class);
    });
});

Route::middleware(['auth'])->prefix('reports')->name('reports.')->group(function(){
   
    //PDFController
    Route::controller(PDFController::class)->prefix('pdfs')->group(function(){
        Route::get('students',  'studentsClassAttendanceReport')->name('pdfs.students');
       
    });
});

Route::middleware(['auth'])->prefix('marks')->name('marks.')->group(function(){
   
    //SubmittedScoreController
    Route::controller(SubmittedScoresController::class)->group(function(){
         Route::get('get-scores', 'getSubmittedScores')->name('submitted-scores.getscores');
         Route::resource('submitted-scores', SubmittedScoresController::class)->except(['edit', 'update']);
    });

     Route::controller(ScoreController::class)->group(function(){
         Route::post('scores/save', 'saveStudentScore')->name('scores.save');
         Route::get('scores/analysis', 'analysis')->name('scores.analysis');
         Route::get('scores/analysed', 'analysedScores')->name('scores.analysed');
         Route::resource('scores', ScoreController::class)->except(['index', 'show']);
    });
});