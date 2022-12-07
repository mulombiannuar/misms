<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Models\Academic\ClassAttendance;
use App\Models\Academic\Form;
use App\Models\Academic\Section;
use App\Models\Academic\StudentAttendance;
use App\Models\Student\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

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
    public function store(Request $request)
    {
        //return $request;
        $section_id = $request->section;
        $date = $request->date;
        $attendanceExists = ClassAttendance::getClassAttendanceBySectionAndDate($section_id, $date);
        if ($attendanceExists) {
            return redirect(route('attendances.class-attendances.index'))->with('danger', 'Class attendance you are trying to create already exists');
        }
        $attendance = new ClassAttendance();
        $attendance->section_id = $section_id ; 
        $attendance->date = $date; 
        $attendance->year = Date('Y'); 
        $attendance->term = $request->input('term'); 
        $attendance->created_by = Auth::user()->id; 
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
       // StudentAttendance::where('attandace_id', $id)->delete();

        //Save audit trail
        $activity_type = 'Class Attendance Deletion';
        $description = 'Succesfully deleted class attendance of id '.$id;
        User::saveUserLog($activity_type, $description);

        return back()->with('success', 'Class attendance data deleted successfully');
    }
}