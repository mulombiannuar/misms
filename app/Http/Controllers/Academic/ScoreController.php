<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentScoreRequest;
use App\Http\Requests\StoreStudentsScoresRequest;
use App\Models\Academic\Exam;
use App\Models\Academic\Form;
use App\Models\Academic\Graphs;
use App\Models\Academic\Score;
use App\Models\Academic\Section;
use App\Models\Admin\DefaultGrade;
use App\Models\Admin\Session;
use App\Models\Student\Student;
use App\Models\User;
use App\Utilities\Utilities;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
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

       $sections = $score->checkIfSectionsHaveSubmittedAllScores($exam_id, $section_numeric);
        //return $score->fetchClassStudentsSingleExamResults($exam_id, $section_numeric);
        //return $score->fetchSectionsStudentsSingleExamResults($exam_id, $section_numeric);
        for ($s=0; $s <count($sections) ; $s++) { 
            if ($sections[$s]->has_submitted ==  false) {
                $pageData = [
                    'exam' => $exam,
                    'page_name' => 'exams',
                    'sections' =>  $sections,
                    'title' => ucwords($exam->name). ' Subjects Entry',
                    'subjects' => $score->getSchoolSubjects(),
                    'form' =>  Form::where('form_numeric', $exam->class_numeric)->first(),
                ];
                return view('admin.academic.marks.sections_submissions', $pageData);
            }
        }

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
            'years' => Session::getYears(),
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
        $graphs = new Graphs();
        $exam = Exam::find($exam_id);
        $defaultGrades = new DefaultGrade();
        $settings = new SettingsController();
        $activeSesion = Session::getActiveSession();
        $report = $this->getReportType($report_type);
        $sections = $score->fetchSectionsAnalysedExamResults($exam_id, $section_numeric, $report_type);
        $classData = $score->fetchClassAnalysedExamResults($exam_id, $section_numeric, $report_type);
        
        //return $score->fetchSectionsAnalysedExamResults($exam_id, $section_numeric, $report_type);
        //return $score->fetchClassAnalysedExamResults($exam_id, $section_numeric, $report_type);
        //return $graphs->getClassSubjectsPeformanceGraph($classData['graphData']);

        $pageData = [
            'exam' => $exam,
			'page_name' => 'exams',
            'sections' =>  $sections,
            'classData' => $classData,
            'school' => $settings->getSchoolDetails(),
            'title' => ucwords($report['report']),
            'subjects' => $score->getSchoolSubjects(),
            'grades' => $defaultGrades->getDefaultGrades(),
            'form' =>  Form::where('form_numeric', $exam->class_numeric)->first(),
            'streams' => $score->getStreamsRankingDetails($exam_id, false),
            'dates' => Session::getClosingAndOpeningDates($activeSesion->session_id),
            'classSubjectsGraph' => $graphs->getClassSubjectsPeformanceGraph($classData['graphData'])
        ]; 
        
        $orientation = 'landscape';
        if($report_type == 8) $orientation = 'portrait';
        $html = view('reports.pdfs.'.$report['view'], $pageData);
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($html);
        $pdf->setPaper('A4', $orientation);
        return $pdf->stream(ucwords($exam->exam_id.'-'.$exam->name).' '.$report['report'].'-'.now().'.pdf', array('Attachment' => 0));
    }

    public function studentReport($student_id, $exam_id)
    {
        $score = new Score();
        $exam = Exam::find($exam_id);
        $settings = new SettingsController();
        $studentData = Student::find($student_id);
        $activeSesion = Session::getActiveSession();

        $student = $score->getStudentAnalysedExamScores($student_id, $exam_id);
        if (is_null($student) || empty($student))  
        return back()->with('warning', 'No analysed scores records found for this student. Make sure you have analysed this exam scores');

         $pageData = [
            'exam' => $exam,
            'student' => $student,
			'page_name' => 'exams',
            'school' => $settings->getSchoolDetails(),
            'subjects' => $score->getSchoolSubjects(),
            'graph' => Graphs::getStudentPeformanceGraph($student_id),
            'dates' => Session::getClosingAndOpeningDates($activeSesion->session_id),
            'title' => ucwords($studentData->admission_no).' Report Card',
        ]; 
      
        $html = view('reports.pdfs.student_report_card', $pageData);
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($html);
        $pdf->setOptions(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true]);
        return $pdf->stream($exam_id.'-'.$student->studentData->student_id.'-reportcard-'.$student->studentData->admission_no.'-'.$exam->year.'.pdf', array('Attachment' => 0));
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
                $view = 'class_broadsheet_report';
                break;

            case 6:
                $report = 'Subjects Improvement';
                $view = 'subjects_analysis_report';
                break;

            case 7:
                $report = 'Students Attendance';
                $view = 'students_attendances_report';
                break;

            case 8:
                $report = 'Students Report Cards';
                $view = 'students_report_cards';
                break;

            case 9:
                $report = 'Streams Improvement';
                $view = 'streams_ranking_report';
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

    private function getMeanReportType($type)
    {
        switch ($type) 
        {
            case 1:
                $report = 'Class Broadsheet';
                $view = 'mean_class_broadsheet_report';
                break;

            case 2:
                $report = 'Grades Distribution';
                $view = 'mean_grades_distribution_report';
                break;

            case 3:
                $report = 'Subjects Analysis';
                $view = 'mean_subjects_analysis_report';
                break;

            case 4:
                $report = 'Streams Ranking';
                $view = 'mean_streams_ranking_report';
                break;

            case 5:
                $report = 'Students Report Cards';
                $view = 'mean_students_report_cards';
                break;
            
            default:
                $report = 'Class Broadsheet';
                $view = 'mean_class_broadsheet_report';
                break;
        }
        return [
            'report' => $report,
            'view' => $view
        ];
    }

    public function termMeanAnalysis()
    {
        //return Session::getYears();
        $pageData = [
			'page_name' => 'exams',
            'years' => Session::getYears(),
            'title' => 'Students Term Mean Scores Analysis',
            'forms' =>  Form::orderBy('form_numeric', 'asc')->get(),
        ];
        return view('admin.academic.marks.term_mean_analysis', $pageData);
    }

    public function termAnalysedScores(Request $request)
    {
        $request->validate([
            'class' => 'required|string',
            'year' => 'required|string',
            'term' => 'required|string'
        ]);
        
        $class_numeric = $request->class;
        $year = $request->year;
        $term = $request->term;
        
        $score = new Score();
        $defaultGrades = new DefaultGrade();
        $exams = Exam::getSingleTermExams($class_numeric, $year, $term);
        if (count($exams) == 0)         
        return back()->with('warning', 'You cannot analysed intended scores since no exams found for the selected class, year and term');

        // $sections = $score->fetchSectionsAnalysedTermAverageExamResults($exams, $class_numeric, $year, $term);;
        //return $score->fetchClassAnalysedTermAverageExamResults($exams, $class_numeric, $year, $term);;
       
        $pageData = [
			'page_name' => 'exams',
            'title' => 'Term '.$term.' '.$year.' Average Exam',
            'subjects' => $score->getSchoolSubjects(),
            'grades' => $defaultGrades->getDefaultGrades(),
            'form' =>  Form::where('form_numeric', $class_numeric)->first(),
            'sections' =>  $score->fetchSectionsStudentsSingleMeanExamResults($exams, $class_numeric, $year, $term),
            'classData' => $score->fetchClassStudentsSingleMeanExamResults($exams, $class_numeric, $year, $term),
        ];
        return view('admin.academic.marks.analysed_scores', $pageData);
    }

    public function meanReportType(Request $request)
    {
        $request->validate([
            'class' => 'required|integer',
            'reporttype' => 'required|string',
            'year' => 'required|integer',
            'term' => 'required|integer',
        ]);

        //return $request;
        $class_numeric = $request->class;
        $report_type = $request->reporttype;
        $year = $request->year;
        $term = $request->term;

        $score = new Score();
        $defaultGrades = new DefaultGrade();
        $exams = Exam::getSingleTermExams($class_numeric, $year, $term);
        if (count($exams) == 0)         
        return back()->with('warning', 'You cannot analysed intended scores since no exams found for the selected class, year and term');
        
        $score = new Score();
        $graphs = new Graphs();
        $defaultGrades = new DefaultGrade();
        $settings = new SettingsController();
        $activeSesion = Session::getActiveSession();
        $report = $this->getMeanReportType($report_type);
        $sections = $score->fetchSectionsAnalysedTermAverageExamResults($exams, $class_numeric, $year, $term);
        $classData = $score->fetchClassAnalysedTermAverageExamResults($exams, $class_numeric, $year, $term);
        $streams = $score->getStreamsMeanRankingDetails($class_numeric, $year, $term);
        
        $pageData = [
            'year' => $year,
            'term' => $term,
            'exams' =>  $exams,
            'streams' => $streams,
			'page_name' => 'exams',
            'sections' =>  $sections,
            'classData' => $classData,
            'school' => $settings->getSchoolDetails(),
            'title' => ucwords($report['report']),
            'subjects' => $score->getSchoolSubjects(),
            'grades' => $defaultGrades->getDefaultGrades(),
            'form' =>  Form::where('form_numeric', $class_numeric)->first(),
            'dates' => Session::getClosingAndOpeningDates($activeSesion->session_id),
            'classSubjectsGraph' => $graphs->getClassSubjectsPeformanceGraph($classData['graphData'])
        ];
        
        if($report_type === 'performance')
        return view('admin.academic.marks.term_performance', $pageData);
        
        $orientation = 'landscape';
        if($report_type == 5) $orientation = 'portrait';
        $html = view('reports.pdfs.'.$report['view'], $pageData);
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($html);
        $pdf->setPaper('A4', $orientation);
        return $pdf->stream(ucwords($report['report'].'-year-'.$year.'-term-').'-'.$term.'-'.now().'.pdf', array('Attachment' => 0));
    }

    public function studentMeanReport($student_id, $year, $term, $class_numeric)
    {
        $score = new Score();
        $settings = new SettingsController();
        $studentData = Student::find($student_id);
        $activeSesion = Session::getActiveSession();
        $exams = Exam::getSingleTermExams($class_numeric, $year, $term);

        $student = $score->getStudentAnalysedMeanExamScores($student_id, $exams, $class_numeric, $year, $term);
        if (is_null($student) || empty($student))  
        return back()->with('warning', 'No analysed scores records found for this student. Make sure you have analysed this exam scores');

         $pageData = [
             'year' => $year,
             'term' => $term,
             'exams' =>  $exams,
             'student' => $student,
             'page_name' => 'exams',
             'school' => $settings->getSchoolDetails(),
             'subjects' => $score->getSchoolSubjects(),
             'exam' => 'Term '.$term.' '.$year.' Report Card',
             'graph' => Graphs::getStudentPeformanceGraph($student_id),
             'dates' => Session::getClosingAndOpeningDates($activeSesion->session_id),
             'title' => ucwords($studentData->admission_no).' Mean Report Card',
        ]; 
      
        $html = view('reports.pdfs.mean_student_report_card', $pageData);
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($html);
        $pdf->setOptions(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true]);
        return $pdf->stream('Year-'.$year.'-term-'.$term.'-'.$student->studentData->student_id.'-mean-reportcard-'.$student->studentData->admission_no.'-'.time().'.pdf', array('Attachment' => 0));
    }



}
