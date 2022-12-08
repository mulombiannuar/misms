<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClassAttendanceRequest;
use App\Http\Requests\StoreStudentsClassAttendanceRequest;
use App\Models\Academic\ClassAttendance;
use App\Models\Academic\Form;
use App\Models\Academic\Section;
use App\Models\Academic\StudentAttendance;
use App\Models\Admin\Session;
use App\Models\Student\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ClassAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageData = [
			'page_name' => 'attendances',
            'title' => 'Class Attendances',
            'attendances' => ClassAttendance::getClassAttendances(),
        ];
        return view('admin.academic.attendances.index', $pageData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageData = [
			'page_name' => 'attendances',
            'title' => 'Add Class Attendance',
            'forms' =>  Form::orderBy('form_numeric', 'asc')->get()
        ];
        return view('admin.academic.attendances.create', $pageData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClassAttendanceRequest $request)
    {
        //return $request;
        $date = $request->date;
        $section_id = $request->section;
        $session = Session::getActiveSession();
        $attendanceExists = ClassAttendance::getClassAttendanceBySectionAndDate($section_id, $date);
        if ($attendanceExists) {
            return redirect(route('attendances.class-attendances.index'))->with('danger', 'Class attendance you are trying to create already exists');
        }
        
        $attendance = new ClassAttendance();
        $attendance->section_id = $section_id ; 
        $attendance->date = $date; 
        $attendance->year = $session->session; 
        $attendance->created_by = Auth::user()->id; 
        $attendance->session_id = $session->session_id; 
        $attendance->save();

        //Save audit trail
        $activity_type = 'Class Attendance Creation';
        $description = 'Created new class attendance dated  '.$attendance->date;
        User::saveUserLog($activity_type, $description);

        return redirect(route('attendances.class-attendances.index'))->with('success', 'Class attendance created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $attendance = ClassAttendance::getClassAttendanceById($id);
        $students = StudentAttendance::getStudentsByAttendanceId($id);
        $pageData = [
			'students' => $students,
            'attendance' => $attendance,
            'page_name' => 'attendances',
            'section' => Section::find($attendance->section_id),
            'sstudents' => Student::getStudentsBySectionId($attendance->section_id),
            'title' => $attendance->section_numeric.$attendance->section_name. ' Class Attendance | '. $attendance->date,
        ];
        return view('admin.academic.attendances.show', $pageData);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ClassAttendance::where('attendance_id', $id)->delete();
        StudentAttendance::where('attendance_id', $id)->delete();

        //Save audit trail
        $activity_type = 'Class Attendance Deletion';
        $description = 'Succesfully deleted class attendance of id '.$id;
        User::saveUserLog($activity_type, $description);

        return back()->with('success', 'Class attendance data deleted successfully');
    }

    //Save students class attendances records to database
    public function storeStudentsClassAttendance(StoreStudentsClassAttendanceRequest $request)
    {
        //return $request;
        // Delete exisiting records first
        DB::table('student_attendances')->where('attendance_id', $request->attendance_id)->delete();
        $classAttendance = ClassAttendance::getClassAttendanceById($request->attendance_id);
        $students = $request->students;
        $comments = $request->comments;
        $status = $request->status;

        for ($s=0; $s <count($students) ; $s++) 
        { 
            $comment = is_null($comments[$s]) ? 'Student was present' : $comments[$s];
            DB::table('student_attendances')->insert([
                'comment' => $comment, 
                'student_id' => $students[$s], 
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'action_by' => Auth::User()->id,
                'attendance_status' => $status[$s], 
                'attendance_id' => $classAttendance->attendance_id, 
            ]);
        }
     
        //Save audit trail
        $activity_type = 'Student Attendances Creation';
        $description = 'Succesfully marked student attendance dated '.$classAttendance->date.' for class '.$classAttendance->section_numeric.$classAttendance->section_name;
        User::saveUserLog($activity_type, $description);
        
        return back()->with('success', 'Succesfully marked student attendance dated '.$classAttendance->date.' for class '.$classAttendance->section_numeric.$classAttendance->section_name);
    }

    //Update Student attendance
    public function updateStudentClassAttendance(Request $request, $id)
    {
        $request->validate([
            'attendance_status' => 'required|integer',
            'comment' => 'string|required'
        ]);
        //return $request;
        $attendance = StudentAttendance::find($id);
        $attendance->comment = $request->comment;
        $attendance->attendance_status = $request->attendance_status;
        $attendance->save();

        //Save audit trail
        $activity_type = 'Student Attendances Updation';
        $description = 'Succesfully updated student attendance of id '.$id;
        User::saveUserLog($activity_type, $description);

        return back()->with('success', 'Student attendance data updated successfully');
    }

    //View student attendance report
    public function studentClassAttendanceReport($student_id)
    {
         return $student_id;
    }
}