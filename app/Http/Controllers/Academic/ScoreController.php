<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\StoreStudentScoreRequest;
use App\Http\Requests\StoreStudentsScoresRequest;
use App\Models\Academic\Exam;
use App\Models\Academic\Form;
use App\Models\Academic\Score;
use App\Models\Academic\Section;
use App\Models\Admin\DefaultGrade;
use App\Models\User;
use App\Utilities\Utilities;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class ScoreController extends Controller
{
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStudentsScoresRequest $request)
    {
        //return print_r($request->all());
        $class_numeric =  $request->input('section_numeric');
        $exam_record_id =  $request->input('exam_record_id');
        $section_id =  $request->input('section_id');
        $subject_id =  $request->input('subject_id');
        $students = $request->input('students');
        $exam_id =  $request->input('exam_id');
        $scores =  $request->input('scores');
        $teacher_id =  Auth::User()->id;

        ///Delete existing record
        DB::table('scores')->where('exam_record_id', $exam_record_id)->delete();

         ///Delete existing record
        DB::table('submitted_scores')->where('subm_id', $exam_record_id)->update([
            'submitted' => 1
        ]);

        ///Save each student score to table marks
         for ($s=0; $s <count($students) ; $s++) {
            DB::table('scores')->insert([
                'year' => Date('Y'),
                'exam_id' => $exam_id, 
                'score' => $scores[$s], 
                'section_id' => $section_id, 
                'subject_id' => $subject_id,
                'teacher_id' => $teacher_id,
                'student_id' => $students[$s], 
                'class_numeric' => $class_numeric,
                'exam_record_id' => $exam_record_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]); 
         }
           
        //Save audit trail
        $activity_type = 'Students Subject Score Creation';
        $description = 'Successfully created student subject scores';
        User::saveUserLog($activity_type, $description);

        //return redirect(route('marks.submitted-scores.show', $exam_record_id))->with('success', 'Subject scores succesfully saved for the selected class and subject');    
        return redirect(route('marks.submitted-scores.index'))->with('success', 'Subject scores succesfully saved for the selected class and subject');    

    }

    //Store single student subject score
    public function saveStudentScore(StoreStudentScoreRequest $request)
    {
        $class_numeric =  $request->input('section_numeric');
        $exam_record_id =  $request->input('exam_record_id');
        $section_id =  $request->input('section_id');
        $subject_id =  $request->input('subject_id');
        $student = $request->input('student');
        $exam_id =  $request->input('exam_id');
        $score =  $request->input('score');
        $teacher_id =  Auth::User()->id;

        //Check of the exam deadline has passed or is closed
        $this->checkIfDeadlineOrIsClosed($exam_id);

        ///Delete existing record
        DB::table('scores')->where(['exam_record_id' => $exam_record_id, 'student_id' => $student])->delete();

        DB::table('scores')->insert([
                'year' => Date('Y'),
                'exam_id' => $exam_id, 
                'score' => $score, 
                'section_id' => $section_id, 
                'subject_id' => $subject_id,
                'teacher_id' => $teacher_id,
                'student_id' => $student, 
                'class_numeric' => $class_numeric,
                'exam_record_id' => $exam_record_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
        ]);

        //Save audit trail
        $activity_type = 'Single Student Subject Score Creation';
        $description = 'Successfully created student subject scores';
        User::saveUserLog($activity_type, $description);

        return redirect(route('marks.submitted-scores.show', $exam_record_id))->with('success', 'Subject scores succesfully saved for the selected class and subject');    
    }

   
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pageData = [
			'page_name' => 'exams',
            'title' => 'Update Student Score',
            'score' => Score::getScoresById($id)
        ];
        return view('admin.academic.marks.edit', $pageData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'score' => 'required|integer'
        ]);
        $score = Score::find($id);

        //Check of the exam deadline has passed or is closed
        $this->checkIfDeadlineOrIsClosed($score->exam_id);

        $score->score = $request->input('score'); 
        $score->save();

        //Save audit trail
        $activity_type = 'Student Score Updation';
        $description = 'Updated student score of id '.$id;
        User::saveUserLog($activity_type, $description);

        return redirect(route('marks.submitted-scores.show', $score->exam_record_id))->with('success', 'Student score updated successfully');
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
        $score = Score::find($id);
        $this->checkIfDeadlineOrIsClosed($score->exam_id);

        Score::where('score_id', $id)->delete();

        //Save audit trail
        $activity_type = 'Student Score Deletion';
        $description = 'Succesfully deleted Student Score of id '.$id;
        User::saveUserLog($activity_type, $description);

        return back()->with('success', 'Student Score deleted successfully');
    }

     /**
     * Check if the specified exam is closed or deadline has passed for modification
     *
     * @param  int  $id
     */
    public function checkIfDeadlineOrIsClosed($id)
    {
        $exam = Exam::find($id);
        $today = Utilities::dateFormat(now());
        $deadline = Utilities::dateFormat($exam->deadline_date);

        if (!Auth::user()->hasRole('admin')) {
            # code...
            if ($today > $deadline) 
            return back()->with('warning', 'You cannot make modification to this exam since deadline has already passed');

            if ($exam->status === 0) 
            return back()->with('warning', 'You cannot make modification to this exam since it has been locked');
       }
       return true;
    }

    public function analysis()
    {
        $pageData = [
			'page_name' => 'exams',
            'title' => 'Students Scores Analysis',
            'forms' =>  Form::orderBy('form_numeric', 'asc')->get(),
        ];
        return view('admin.academic.marks.analysis', $pageData);
    }

    public function analysedScores(Request $request)
    {
        $request->validate([
            'section_numeric' => 'required|integer',
            'exams' => 'required|integer',
        ]);

        $section_numeric = $request->section_numeric;
        $exam_id = $request->exams;

        $score = new Score();
        $exam = Exam::find($exam_id);
        $defaultGrades = new DefaultGrade();

        //return $score->fetchClassStudentsSingleExamResults($exam_id, $section_numeric);
        //return $score->fetchSectionsStudentsSingleExamResults($exam_id, $section_numeric);

        $pageData = [
            'exam' => $exam,
			'page_name' => 'exams',
            'title' => ucwords($exam->name),
            'subjects' => $score->getSchoolSubjects(),
            'grades' => $defaultGrades->getDefaultGrades(),
            'form' =>  Form::where('form_numeric', $exam->class_numeric)->first(),
            'sections' =>  $score->fetchSectionsStudentsSingleExamResults($exam_id, $section_numeric),
            'classData' => $score->fetchClassStudentsSingleExamResults($exam_id, $section_numeric),
        ];
        return view('admin.academic.marks.analysed_scores', $pageData);
    }

    public function reports()
    {
        $pageData = [
			'page_name' => 'exams',
            'title' => 'Exam Reports',
            'forms' =>  Form::orderBy('form_numeric', 'asc')->get(),
        ];
        return view('admin.academic.marks.reports', $pageData);
    }

    public function reportType(Request $request)
    {
        $request->validate([
            'section_numeric' => 'required|integer',
            'report_type' => 'required|integer',
            'exams' => 'required|integer',
        ]);

        $section_numeric = $request->section_numeric;
        $report_type = $request->report_type;
        $exam_id = $request->exams;

        $scores = DB::table('students_analysed_scores')->where('exam_id', $exam_id)->get();
        if(count($scores) == 0) 
        return back()->with('warning', 'You cannot view intended reports since no analysed scores have been found for the selected exam');
        
        $score = new Score();
        $exam = Exam::find($exam_id);
        $defaultGrades = new DefaultGrade();
        $report = $this->getReportType($report_type);
        
        //return $score->fetchSectionsAnalysedExamResults($exam_id, $section_numeric);
        //return $score->fetchClassAnalysedExamResults($exam_id, $section_numeric);

        $pageData = [
            'exam' => $exam,
			'page_name' => 'exams',
            'title' => ucwords($report['report']),
            'subjects' => $score->getSchoolSubjects(),
            'grades' => $defaultGrades->getDefaultGrades(),
            'sections' => Section::getSectionsByClassNumeric($section_numeric),
            'form' =>  Form::where('form_numeric', $exam->class_numeric)->first(),
            'sections' =>  $score->fetchSectionsAnalysedExamResults($exam_id, $section_numeric),
            'classData' => $score->fetchClassAnalysedExamResults($exam_id, $section_numeric),
        ];
        
        $html = view('reports.pdfs.'.$report['view'], $pageData);
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($html);
        $pdf->setPaper('A4','landscape');
        return $pdf->stream(ucwords($exam->exam_id.'-'.$exam->name).' '.$report['report'].'.pdf', array('Attachment' => 0));
    }

    private function getReportType($type)
    {
        switch ($type) 
        {
            case 1:
                $report = 'Class Broadsheet';
                $view = 'class_broadsheet_report';
                break;

            case 2:
                $report = 'Grades Distribution';
                $view = 'grades_distribution_report';
                break;

            case 3:
                $report = 'Subjects Analysis';
                $view = 'subjects_analysis_report';
                break;

            case 4:
                $report = 'Streams Ranking';
                $view = 'streams_ranking_report';
                break;

            case 5:
                $report = 'Students Improvement';
                $view = 'students_improvement_report';
                break;

            case 6:
                $report = 'Subjects Improvement';
                $view = 'subjects_improvement_report';
                break;

            case 7:
                $report = 'Students Attendance';
                $view = 'students_attendances_report';
                break;

            case 8:
                $report = 'Students Report Cards';
                $view = 'students_report_cards';
                break;
            
            default:
                $report = 'Class Broadsheet';
                $view = 'class_broadsheet_report';
                break;
        }
        return [
            'report' => $report,
            'view' => $view
        ];
    }

}
