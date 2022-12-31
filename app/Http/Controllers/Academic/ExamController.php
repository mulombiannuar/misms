<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExamRequest;
use App\Http\Requests\UpdateExamRequest;
use App\Models\Academic\Exam;
use App\Models\Academic\Form;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageData = [
			'page_name' => 'exams',
            'title' => 'Manage Exams',
            'exams' => Exam::getExams(),
        ];
        return view('admin.academic.exams.index', $pageData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageData = [
			'page_name' => 'exams',
            'title' => 'Add New Exam',
            'forms' =>  Form::orderBy('form_numeric', 'asc')->get()
        ];
        return view('admin.academic.exams.create', $pageData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreExamRequest $request)
    {
        $exam = new Exam;
        $exam->year = Date('Y');
        $exam->status = 1;
        $exam->created_by = Auth::User()->id;
        $exam->name = $request->input('name'); 
        $exam->term = $request->input('term'); 
        $exam->notes = $request->input('notes'); 
        $exam->converted = $request->input('converted'); 
        $exam->conversion = $request->input('conversion'); 
        $exam->end_date = $request->input('end_date'); 
        $exam->start_date = $request->input('start_date'); 
        $exam->deadline_date = $request->input('deadline_date'); 
        $exam->class_numeric = $request->input('class_numeric'); 
        $exam->save();

        //Save audit trail
        $activity_type = 'New Exam Creation';
        $description = 'Created new exam  '.$exam->name;
        User::saveUserLog($activity_type, $description);

        return redirect(route('admin.exams.index'))->with('success', 'Exams data saved successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $exam = Exam::find($id);
        $pageData = [
			'page_name' => 'exams',
            'exam' =>  $exam,
            'title' => ucwords($exam->name.'-Term'.$exam->term.'-'.$exam->year),
            'forms' =>  Form::orderBy('form_numeric', 'asc')->get()
        ];
        return view('admin.academic.exams.show', $pageData);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $exam = Exam::find($id);
        $pageData = [
            'exam' =>  $exam,
            'page_name' => 'exams',
            'title' => ucwords($exam->name.'-Term'.$exam->term.'-'.$exam->year),
            'forms' =>  Form::orderBy('form_numeric', 'asc')->get()
        ];
        return view('admin.academic.exams.edit', $pageData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateExamRequest $request, $id)
    {
        $exam = Exam::find($id);
        $exam->name = $request->input('name'); 
        $exam->term = $request->input('term'); 
        $exam->notes = $request->input('notes'); 
        $exam->converted = $request->input('converted'); 
        $exam->end_date = $request->input('end_date'); 
        $exam->start_date = $request->input('start_date'); 
        $exam->deadline_date = $request->input('deadline_date'); 
        $exam->conversion = $request->input('conversion'); 
        $exam->class_numeric = $request->input('class_numeric'); 
        $exam->save();

        //Save audit trail
        $activity_type = 'Exam Updation';
        $description = 'Updated exam  '.$exam->name;
        User::saveUserLog($activity_type, $description);

        return back()->with('success', 'Exam data updated successfully');
    }

    public function activateExam(Request $request, Exam $exam)
    {
        $exam->status = 1;
        $exam->save();

        //Save audit trail
        $activity_type = 'Exam Activation';
        $description = 'Activated exam  '.$exam->name;
        User::saveUserLog($activity_type, $description);

        return back()->with('success', 'Exam activated successfully');  
    }

    public function deactivateExam(Request $request, Exam $exam)
    {
        $exam->status = 0;
        $exam->save();

        //Save audit trail
        $activity_type = 'Exam Deactivation';
        $description = 'Deactivated exam  '.$exam->name;
        User::saveUserLog($activity_type, $description);
        return back()->with('success', 'Exam deactivated successfully');  
    }

    public function openExam(Request $request, Exam $exam)
    {
        $exam->is_closed = 0;
        $exam->save();

        //Save audit trail
        $activity_type = 'Exam Opening';
        $description = 'Opened exam '.$exam->name;
        User::saveUserLog($activity_type, $description);

        return back()->with('success', 'Exam opened successfully');  
    }

    public function closeExam(Request $request, Exam $exam)
    {
        $exam->is_closed = 1;
        $exam->save();

        //Save audit trail
        $activity_type = 'Exam Closure';
        $description = 'Closed exam '.$exam->name;
        User::saveUserLog($activity_type, $description);

        return back()->with('success', 'Exam closed successfully');  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Exam::where('exam_id', $id)->delete();

        //Save audit trail
        $activity_type = 'Exam Deletion';
        $description = 'Deleted existing exam of id '.$id;
        User::saveUserLog($activity_type, $description);

        return back()->with('success', 'Subject data deleted successfully');
    }
}