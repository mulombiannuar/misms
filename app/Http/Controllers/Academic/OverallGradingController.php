<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOverllGradingRequest;
use App\Models\Academic\Form;
use App\Models\Academic\OverallGrading;
use App\Models\Admin\DefaultGrade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OverallGradingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $grade = new DefaultGrade();
        $pageData = [
			'page_name' => 'gradings',
            'title' => 'Manage Subjects Gradings',
            'grades' => $grade->getDefaultGrades(),
            'forms'=> Form::orderBy('form_numeric', 'asc')->get(),
        ];
        return view('admin.academic.subjects.overall_gradings', $pageData); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOverllGradingRequest $request)
    {
        $min_score = $request->input('min_score');
        $max_score = $request->input('max_score');
        $grade_name = $request->input('grade_name');
        $form_numeric = $request->input('form_numeric');
        $score_remarks = $request->input('score_remarks');
        $principal_remarks = $request->input('principal_remarks');

        $gradingExist = OverallGrading::where([
                'form_numeric' => $request->input('form_numeric')
        ])->get();

        if (!empty($gradingExist)) {
                OverallGrading::where([
                'form_numeric' => $request->input('form_numeric')
            ])->delete();
        }

        for ($count=0; $count <count($grade_name) ; $count++) 
        { 
            DB::table('overall_gradings')->insert([
                'form_numeric' => $form_numeric,
                'min_score' => $min_score[$count],
                'max_score' => $max_score[$count],
                'grade_name' =>  $grade_name[$count],
                'teacher_remarks' => $score_remarks[$count],
                'principal_remarks' => $principal_remarks[$count],
                'created_by' => Auth::User()->id
            ]);
        }
         
        //Save user log
        $activity_type = 'Overall Grading Saving';
        $description = 'Created overall grading for class '.$form_numeric;
        User::saveUserLog($activity_type, $description);

        return back()->with('success', 'Overall Grading data saved successfully for class '.$form_numeric);
    }

    
}