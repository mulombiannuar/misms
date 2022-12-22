<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubmittedScoresRequest;
use App\Models\Academic\Form;
use App\Models\Academic\Score;
use App\Models\Academic\Section;
use App\Models\Academic\Subject;
use App\Models\Academic\SubmittedScore;
use App\Models\Admin\Session;
use App\Models\Student\Student;
use App\Models\Student\StudentSubject;
use App\Models\User;
use App\Utilities\Buttons;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

use function PHPUnit\Framework\isEmpty;

class SubmittedScoresController extends Controller
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
            'title' => 'Students Scores Records',
            'forms' =>  Form::orderBy('form_numeric', 'asc')->get(),
            'subjects' => Subject::orderBy('subject_name', 'asc')->get(),
        ];
        return view('admin.academic.marks.index', $pageData);
    }

    public function getSubmittedScores()
    {
        $scores = SubmittedScore::getSubmittedScores();
        return DataTables::of($scores)
                         ->addIndexColumn()
                         ->addColumn('show', function ($score) {
                            return Buttons::dataTableShowButton(route('marks.submitted-scores.show', $score->subm_id));
                           })
                         ->addColumn('delete', function ($score) {
                            return Buttons::dataTableDeleteButton(route('marks.submitted-scores.show', $score->subm_id));
                           })
                         ->rawColumns(['show','delete'])
                         ->make(true);
    }

   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubmittedScoresRequest $request)
    {
        $section_id = $request->section;
        $subject_id = $request->subject;
        $exam_id = $request->exams;
        $class_numeric = $request->section_numeric;
        $session = Session::getActiveSession();
        $subject = Subject::find($subject_id);
        $scoreExists = SubmittedScore::where([
            'exam_id' => $exam_id, 
            'subject_id' => $subject_id, 
            'section_id' => $section_id])->first();
        if($scoreExists) return redirect(route('marks.submitted-scores.show', $scoreExists->subm_id))->with('warning', 'Students Scores Records already exists');
        
        $scores = new SubmittedScore();
        $scores->numbers = 0; 
        $scores->exam_id = $exam_id; 
        $scores->section_id = $section_id ; 
        $scores->subject_id = $subject_id; 
        $scores->session = $session->session; 
        $scores->class_numeric = $class_numeric; 
        $scores->teacher_id = Auth::user()->id; 
        $scores->session = $session->session_id; 
        $scores->save();

        //Save audit trail
        $activity_type = 'Students Scores Records Creation';
        $description = 'Created new Students Scores Records for '.$subject->subject_name;
        User::saveUserLog($activity_type, $description);

        return redirect(route('marks.submitted-scores.show',  $scores->subm_id))->with('success', 'Students Scores Records created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $score = SubmittedScore::getSubmittedScoreById($id);
        $pageData = [
            'score' =>  $score,
			'page_name' => 'exams',
            'section' => Section::find($score->section_id),
            'students_score' => Score::getStudentsScoresByExamRecord($score->subm_id),
            'subject_students' => StudentSubject::getSubjectStudentsBySectionId($score->section_id, $score->subject_id),
            'title' => $score->subject_name.' Subject Score Records | '.$score->section_numeric.$score->section_name,
        ];
        return view('admin.academic.marks.show', $pageData);
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         //Check of the exam deadline has passed or is closed
        $sub = SubmittedScore::find($id);
        $score = new ScoreController();
        $score->checkIfDeadlineOrIsClosed($sub->exam_id);

        SubmittedScore::where('subm_id', $id)->delete();
        Score::where('exam_record_id', $id)->delete();

        //Save audit trail
        $activity_type = 'Students Scores Records Deletion';
        $description = 'Succesfully deleted Students Scores Records of id '.$id;
        User::saveUserLog($activity_type, $description);

        return back()->with('success', 'Students Scores Records data deleted successfully');
    }
}
