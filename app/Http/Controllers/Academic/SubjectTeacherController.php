<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubjectTeacherRequest;
use App\Models\Academic\Form;
use App\Models\Academic\SubjectTeacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectTeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teacher = new SubjectTeacher();
        $pageData = [
			'page_name' => 'academics',
            'title' => 'Manage Subjects Teachers',
            'subjects' => $teacher->getSubjectsTeachers()
        ];
        return view('admin.academic.subjects.subject_teachers', $pageData);
    }

  

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubjectTeacherRequest $request)
    {
        //Ensures a teacher cannot have the same subject more than once
        if (SubjectTeacher::where([
            'user_id' => $request->input('user_id'),
            'section_id' => $request->input('section'),
            'subject_id' => $request->input('subject') 
           ])->first()) {
            return back()->with('danger', 'The selected teacher already exists with the subject in the class');
        }

        //Ensures that only one subject is assigned to teacher at ago
        if (SubjectTeacher::where([
                'section_id' => $request->input('section'),
                'subject_id' => $request->input('subject') 
            ])->first()) {
            return back()->with('danger', 'Teacher already exists with the same subject');
        }

        $subject = new SubjectTeacher;
        $subject->user_id = $request->input('user_id'); 
        $subject->subject_id = $request->input('subject'); 
        $subject->section_id = $request->input('section'); 
        $subject->created_by = Auth::User()->id; 
        $subject->save();

        //Save audit trail
        $activity_type = 'Subject Teacher Creation';
        $description = 'Created new subject teacher with id '.$subject->sub_id;
        User::saveUserLog($activity_type, $description);

        return back()->with('success', 'Subject successfully assigned to this teacher');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        SubjectTeacher::where('sub_id', $id)->delete();

        //Save audit trail
        $activity_type = 'Subject Teacher Deletion';
        $description = 'Deleted existing subject teacher of id '.$id;
        User::saveUserLog($activity_type, $description);

        return back()->with('success', 'Subject teacher data deleted successfully');
    }
}