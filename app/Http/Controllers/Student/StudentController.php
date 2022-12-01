<?php

namespace App\Http\Controllers\Student;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentPhotoRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Academic\Form;
use App\Models\Hostel\Hostel;
use App\Models\Student\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Utilities\Buttons;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return $this->getStudents();
        $pageData = [
			'page_name' => 'students',
            'title' => 'Manage Students',
            'students' => [],
        ];
        return view('admin.student.index', $pageData);
    }

    public function getStudents()
    {
        $users = Student::getStudents();
        return DataTables::of($users)
                        ->addIndexColumn()
                        ->addColumn('action', function ($user) {
                            return Buttons::dataTableButtons(
                                route('students.students.show', $user->id),
                                route('students.students.edit', $user->id),
                                route('admin.users.destroy', $user->id),
                            );
                        })
                        ->addColumn('action_view', function ($user) {
                            return Buttons::activateDeactivateButton(
                                $user->status, 
                                route('admin.users.activate', $user->id),
                                route('admin.users.deactivate', $user->id)
                            );
                        })
                        ->addColumn('reset', function ($user) {
                            return Buttons::resetButton(route('students.reset-password', $user->id));
                        })
                        ->rawColumns(['action','action_view', 'reset'])
                        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageData = [
			'page_name' => 'students',
            'title' => 'Add New Student',
            'forms' =>  Form::orderBy('form_numeric', 'asc')->get(),
            'hostels' => Hostel::orderBy('hostel_name', 'asc')->get(),
            'counties' => DB::table('counties')->orderBy('county_name', 'asc')->get(),
        ];
        return view('admin.student.create', $pageData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStudentRequest $request)
    {
        $studentData = [
            'name' => ucwords($request->name),
            'email' => $this->makeStudentEmail($request->admission_no),
            'password' => $this->setStudentPassword(),
            'password_confirmation' => $this->setStudentPassword(),
            'is_student' => 1
        ];
        $createStudent = new CreateNewUser();
        $newStudent = $createStudent->create($studentData);
        $newStudent->attachRole('student');

        $studentImage = strtolower($request->gender) == 'male' ? 'icon-male.png' : 'icon-female.png';

        if($request->hasFile('student_image')) 
        {
            $fileNameWithExt = $request->file('student_image')->getClientOriginalName();

            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            
            $extension = $request->file('student_image')->getClientOriginalExtension();
            
            $studentImage = $fileName.'_'.time().'.'.$extension;

            $request->file('student_image')->storeAs('public/assets/dist/img/users', $studentImage);
        }
        
        $student = new Student();
        $student->status = 1;
        $student->student_image = $studentImage;
        $student->student_user_id = $newStudent->id;
        $student->upi = $request->input('upi');
        $student->ward = $request->input('ward'); 
        $student->extra = $request->input('extra');
        $student->admission_no = $request->input('admission_no');
        $student->gender = $request->input('gender');
        $student->county_id = $request->input('county');
        $student->address = $request->input('address');
        $student->impaired = $request->input('impaired');
        $student->religion = $request->input('religion');
        $student->admission_date = $request->input('admission_date');
        $student->sub_county = $request->input('sub_county');
        $student->birth_date = $request->input('birth_date');
        $student->section_id = $request->input('section');
        $student->kcpe_year = $request->input('kcpe_year');
        $student->kcpe_marks = $request->input('kcpe_marks');
        $student->primary_school = $request->input('primary_school');
        $student->save();

        //Save user log
        $activity_type = 'Student Admission';
        $description = 'Created new student of admission number '.$request->admission_no;
        User::saveUserLog($activity_type, $description);

        return redirect(route('students.students.index'))->with('success' , 'Student data created successfully. Please proceed by assigning subjects to this student');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $student = Student::getStudentById($id);
        $pageData = [
            'user' => $student,
			'page_name' => 'students',
            'title' => ucwords($student->name.'-'.$student->admission_no),
            'forms' =>  Form::orderBy('form_numeric', 'asc')->get(),
            'hostels' => Hostel::orderBy('hostel_name', 'asc')->get(),
            'counties' => DB::table('counties')->orderBy('county_name', 'asc')->get(),
        ];
        return view('admin.student.edit', $pageData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStudentRequest $request, $id)
    {
        $user = User::find($id);
        $user->name = $request->name;
        $user->email =  $this->makeStudentEmail($request->admission_no);
        $user->save();

        $student = Student::find($user->student->student_id);
        $student->upi = $request->input('upi');
        $student->ward = $request->input('ward'); 
        $student->extra = $request->input('extra');
        //$student->admission_no = $request->input('admission_no');
        $student->gender = $request->input('gender');
        $student->county_id = $request->input('county');
        $student->address = $request->input('address');
        $student->impaired = $request->input('impaired');
        $student->religion = $request->input('religion');
        $student->admission_date = $request->input('admission_date');
        $student->sub_county = $request->input('sub_county');
        $student->birth_date = $request->input('birth_date');
        $student->section_id = $request->input('section');
        $student->kcpe_year = $request->input('kcpe_year');
        $student->kcpe_marks = $request->input('kcpe_marks');
        $student->primary_school = $request->input('primary_school');
        $student->save();

                //Save user log
        $activity_type = 'Student Details Updation';
        $description = 'Updated student details of admission number '.$student->admission_no;
        User::saveUserLog($activity_type, $description);

        return back()->with('success', 'Student details updated successfuly');
    }

    public function updateStudentPhoto(UpdateStudentPhotoRequest $request, $id)
    {
        $student = Student::find($id);
        $studentImage = $student->student_image;
        if($request->hasFile('student_image')) 
        {
            if ($studentImage != 'icon-female.png' || $studentImage != 'icon-male.png') {
                Storage::delete('public/assets/dist/img/users/'.$student->user_image);
            }

            $fileNameWithExt = $request->file('student_image')->getClientOriginalName();

            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            
            $extension = $request->file('student_image')->getClientOriginalExtension();
            
            $studentImage = $fileName.'_'.time().'.'.$extension;

            $request->file('student_image')->storeAs('public/assets/dist/img/users', $studentImage);
        }

        $student->student_image = $studentImage;
        $student->save();

        //Save user log
        $activity_type = 'Student Photo Upload';
        $description = 'Updated student photo of admission number '.$student->admission_no;
        User::saveUserLog($activity_type, $description);

        return back()->with('success', 'Student photo uploaded successfully');  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function makeStudentEmail($admission_no)
    {
        $settings = new SettingsController;
        $school = $settings->getSchoolDetails();

        return strtolower(htmlspecialchars($admission_no).'@'.$school->domain);
    }

    public function resetPassword($id)
    {
        $user = User::find($id);
        $user->forceFill([
            'password' => Hash::make($this->setStudentPassword()),
        ])->save();

        //Save user log
        $activity_type = 'Reset Student Password';
        $description = 'Reset password for the student of the name '.$user->name. '. The new password is Student@123';
        User::saveUserLog($activity_type, $description);

        return back()->with('success', 'Password changed successfully for the student. The new password is Student@123');
    }

    private function setStudentPassword()
    {
        return 'Student@123';
    }
}