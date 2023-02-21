<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubjectGradingRequest;
use App\Models\Academic\Form;
use App\Models\Academic\Subject;
use App\Models\Academic\SubjectGrading;
use App\Models\Admin\DefaultGrade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubjectGradingController extends Controller
{
    //Subject grading for each class
    public function index()
    {
        $grade = new DefaultGrade();
        //return  $grade->getDefaultGrades();
        $pageData = [
			'page_name' => 'gradings',
            'title' => 'Manage Subjects Gradings',
            'grades' => $grade->getDefaultGrades(),
            'forms'=> Form::orderBy('form_numeric', 'asc')->get(),
            'subjects' => Subject::orderBy('subject_name', 'asc')->get(),
        ];
        return view('admin.academic.subjects.subject_gradings', $pageData); 
    }

    public function store(StoreSubjectGradingRequest $request)
    {
        $min_score = $request->input('min_score');
        $max_score = $request->input('max_score');
        $subject_id = $request->input('subject_id');
        $grade_name = $request->input('grade_name');
        $form_numeric = $request->input('form_numeric');
        $score_remarks = $request->input('score_remarks');

        $subject = Subject::find($subject_id);
        
        $gradingExist = SubjectGrading::where([
            'subject_id' => $request->input('subject_id'),
            'form_numeric' => $request->input('form_numeric')
        ])->get();

        if (!empty($gradingExist)) {
                SubjectGrading::where([
                'subject_id' => $request->input('subject_id'),
                'form_numeric' => $request->input('form_numeric')
            ])->delete();
        } 
        
        for ($count=0; $count <count($grade_name) ; $count++) 
        { 
            DB::table('subject_gradings')->insert([
                'min_score' => $min_score[$count],
                'max_score' => $max_score[$count],
                'grade_name' => $grade_name[$count],
                'score_remarks' => $score_remarks[$count],
                'subject_id' => $subject_id,
                'form_numeric' => $form_numeric,
                'created_by' => Auth::User()->id
            ]);
        }

        //Save user log
        $activity_type = 'Subject Grading Saving';
        $description = 'Created '.$subject->subject_name.' subject grading for class '.$form_numeric;
        User::saveUserLog($activity_type, $description);

        return back()->with('success', 'Grading data saved successfully for '.$subject->subject_name);
    }
}