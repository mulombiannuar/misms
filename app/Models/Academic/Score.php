<?php

namespace App\Models\Academic;

use App\Models\Admin\DefaultGrade;
use App\Models\Student\Student;
use App\Models\Student\StudentSubject;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use stdClass;

class Score extends Model
{
    use HasFactory;
    protected $table = 'scores';
    protected $primaryKey = 'score_id';

    public function getSchoolSubjects()
    {
       return DB::table('subjects')->where('deleted_at', null)->orderBy('subject_name', 'asc')->get()->all();
    }

    public function getSubjectsByOptionality($optionality)
    {
       return Subject::select('*')->where('optionality', $optionality)->orderBy('subject_name', 'asc')->get()->all();
    }

    private function getStudentSubjectsCombination($student_id)
    {
       return DB::table('student_subjects')
                 ->join('subjects', 'student_subjects.subject_id', '=', 'subjects.subject_id')
                 ->where('student_id', $student_id)
                 ->select('*')
                 ->get();
    }

    public static function getStudentsScoresByExamRecord($record_id)
    {
        $subjectGrading = new SubjectGrading();
        $scores = DB::table('scores')
                    ->where('exam_record_id', $record_id)
                    ->join('students', 'students.student_id', '=', 'scores.student_id' )
                    ->join('users', 'users.id', '=', 'students.student_user_id' )
                    ->select('students.admission_no', 'scores.*', 'users.name')
                    ->orderBy('admission_no', 'asc')
                    ->get();

        for ($score=0; $score <count($scores) ; $score++) 
        { 
            $scores[$score]->grade = $subjectGrading->getSubjectGrading($scores[$score]->score, $scores[$score]->subject_id, $scores[$score]->class_numeric);
        }
        return $scores;
    }

    public static function getScoresById($id)
    {
        return  DB::table('scores')
                  ->where('score_id', $id)
                  ->join('students', 'students.student_id', '=', 'scores.student_id' )
                  ->join('users', 'users.id', '=', 'students.student_user_id' )
                  ->select('students.admission_no', 'scores.*', 'users.name')
                  ->first();
    }

   //get class students single exam results
   public function fetchClassStudentsSingleExamResults($exam_id, $section_numeric)
   {
      $students = Student::getStudentsByClassNumeric($section_numeric)->toArray();
      $studentScores = $this->getStudentsScores($students, $exam_id);
      usort($students, array($this, 'sortStudentsByPoints'));

      $gradesData = $this->getGradeDistribution($students);
      $subjects =  $this->getSubjectsAnalysis($students, $section_numeric);
      $graphData = $this->getSubjectsAnalysisGraphData($subjects);

      //Save students overall class position
      $this->saveStudentsOverallPosition($students, $exam_id);

      //Save analysed grades data
      $this->saveAnalysedGradesDistribution($gradesData, $exam_id, null, count($studentScores));

      //Save analysed subjects
      $this->saveSubjectsAnalysis($subjects, $exam_id, null);

      //Save graph data
      $this->saveGraphData($graphData, $exam_id, null);

      return [
         'students' => $students,
         'gradesData' => $gradesData,
         'subjects' => $subjects,
         'graphData' => $graphData
      ];
   }

   //get class analysed students single exam results
   public function fetchClassAnalysedExamResults($exam_id, $section_numeric, $report_type)
   {
      $students = Student::getStudentsByClassNumeric($section_numeric)->toArray();
      $this->getStudentsAnalysedScores($students, $exam_id, $report_type);
      usort($students, array($this, 'sortStudentsByPoints'));

      $gradesData = $this->getAnalysedGradesDistribution(null, $exam_id);

      return [
         'students' => $students,
         'gradesData' => $gradesData,
         'subjects' => $this->getAnalysedClassSubjects(null, $exam_id, $report_type),
         'graphData' => $this->getAnalysedGraphData(null, $exam_id),
         'examDev' => $this->calculateClassDeviation($gradesData, null)
      ];
   }

   //check if all sections have submitted their subject scores
   public function checkIfSectionsHaveSubmittedAllScores($exam_id, $section_numeric)
   {
      $sections = Section::getSectionsByClassNumeric($section_numeric);
      for ($s=0; $s <count($sections) ; $s++) { 
         $subjects = StudentSubject::getSectionSubjects($sections[$s]->section_id);
         $sections[$s]->subjects = $this->sectionSubjectsSubmissionStatus($subjects, $exam_id, $sections[$s]->section_id);
         $sections[$s]->has_submitted = $this->hasSubmitted($sections[$s]->subjects);
      }
      return $sections;
   }

   //section subjects submission status
   private function sectionSubjectsSubmissionStatus($subjects, $exam_id, $section_id)
   {
      $score =  new SubmittedScore();
      $submittedScores = $score->getSectionSubmittedScores($section_id, $exam_id);
      for ($sub=0; $sub < count($subjects); $sub++) { 
          $subjects[$sub]->submission_id = null;
          $subjects[$sub]->submitted_date = null;
          $subjects[$sub]->teacher = null;
          $subjects[$sub]->is_submitted = false;

          for ($score=0; $score <count($submittedScores) ; $score++) { 
            if ($subjects[$sub]->subject_id == $submittedScores[$score]->subject_id) {
               $subjects[$sub]->submission_id = $submittedScores[$score]->subm_id;
               $subjects[$sub]->submitted_date = $submittedScores[$score]->created_at;
               $subjects[$sub]->teacher = $submittedScores[$score]->name;
               $subjects[$sub]->is_submitted = true;
            }
          }
      }
      return $subjects;
   }

   //has submitted
   private function hasSubmitted($subjects)
   {
      for ($s=0; $s <count($subjects) ; $s++) { 
         if ($subjects[$s]->is_submitted == false) {
            return false;
            break;
         }
      }
      return true;
   }

   //Get section single exam results
   public function fetchSectionsStudentsSingleExamResults($exam_id, $section_numeric)
   {
      $sections = Section::getSectionsByClassNumeric($section_numeric);
      for ($s=0; $s <count($sections) ; $s++) {
          $students = Student::getStudentsBySectionId($sections[$s]->section_id)->toArray();
          $sections[$s]->students = $this->getStudentsScores($students, $exam_id);
          $sections[$s]->gradesData = $this->getGradeDistribution($sections[$s]->students);
          $sections[$s]->subjects = $this->getSubjectsAnalysis($sections[$s]->students, $section_numeric);
          $sections[$s]->graphData = $this->getSubjectsAnalysisGraphData($sections[$s]->subjects);

          //Save students analysed exam
          $this->saveAnalysedStudentExam($sections[$s]->students, $exam_id, $sections[$s]->section_id, count($sections[$s]->students));

          //Save analysed grades data
          $this->saveAnalysedGradesDistribution($sections[$s]->gradesData, $exam_id, $sections[$s]->section_id, count($sections[$s]->students));

          //Save analysed grades data
          $this->saveSubjectsAnalysis($sections[$s]->subjects, $exam_id, $sections[$s]->section_id);

          //Save graph data
          $this->saveGraphData($sections[$s]->graphData, $exam_id, $sections[$s]->section_id);
      }
      return $sections;
   }

   //Get section analysed single exam results
   public function fetchSectionsAnalysedExamResults($exam_id, $section_numeric, $report_type)
   {
      $sections = Section::getSectionsByClassNumeric($section_numeric);
      for ($s=0; $s <count($sections) ; $s++) {
          $students = Student::getStudentsBySectionId($sections[$s]->section_id)->toArray();
          $sections[$s]->students = $this->getStudentsAnalysedScores($students, $exam_id, $report_type);
          $sections[$s]->gradesData = $this->getAnalysedGradesDistribution($sections[$s]->section_id, $exam_id);
          $sections[$s]->subjects = $this->getAnalysedClassSubjects($sections[$s]->section_id, $exam_id, $report_type);
          $sections[$s]->graphData = $this->getAnalysedGraphData($sections[$s]->section_id, $exam_id);
          $sections[$s]->examDev = $this->calculateClassDeviation($sections[$s]->gradesData, $exam_id);
      }
      return $sections;
   }

   // Get scores for students in a given exam
   private function getStudentsScores($students, $exam_id)
   {
        $exam = Exam::find($exam_id);
        $form = Form::where('form_numeric', $exam->class_numeric)->first(); 
        for ($s=0; $s <count($students) ; $s++) 
        { 
            $studentSubject = new StudentSubject();
            $students[$s]->subjectScores = [];
            $students[$s]->subjectsEntry = 0;

            $scores = $this->fetchStudentSubjectScores($students[$s]->student_id, $exam_id);
            //$subjectsCombination = $studentSubject->getStudentSubjects($students[$s]->student_user_id);
            $students[$s]->subjectScores = $this->getStudentSubjectsScores($students[$s]->student_id, $exam_id);
            $students[$s]->subjectsEntry = $this->getStudentSubjectEntry($scores);
            $students[$s]->points = $this->calculateTotalPoints($scores, $exam_id);
            $students[$s]->averagePoints = $students[$s]->points->averagePoints;
        }

        //Loop through students with already assigned properties to assign them positions
        for ($stud=0; $stud <count($students) ; $stud++)
        { 
           $students[$stud]->classPosition = $this->assignStudentPosition($students[$stud]->student_id, $students, $form->min_subs);
        }

       usort($students, array($this, 'sortStudentsByPoints'));
       return $students;
   }

   //get student analysed exam scores
   public function getStudentAnalysedExamScores($student_id, $exam_id)
   {
      $student = new stdClass;
      $student->studentData = Student::getStudentByStudentId($student_id);
      $student->subjectScores = $this->getStudentAnalysedSubjectsScores($student_id, $exam_id);
      $student->examDetails = $this->fetchStudentAnalysedExamDetails($student_id, $exam_id);
      $student->averagePoints = $student->examDetails->average_points;
      $student->studentDev = $this->calculateStudentDeviation($student->examDetails, $student_id);
      $student->classTeacher = $this->getClassTeacher($student->examDetails);
      $student->classEntries = $this->getClassEntries($exam_id, $student->examDetails->section_id );
      $student->otherExams = $this->fetchStudentAnalysedExams($student_id, $exam_id);
      return $student;
   }

   // get students analysed exam scores
   private function getStudentsAnalysedScores($students, $exam_id, $report_type)
   {
      for ($s=0; $s <count($students) ; $s++) 
      { 
         $students[$s]->subjectScores = $this->getStudentAnalysedSubjectsScores($students[$s]->student_id, $exam_id);
         $students[$s]->examDetails = $this->fetchStudentAnalysedExamDetails($students[$s]->student_id, $exam_id);
         $students[$s]->averagePoints = $students[$s]->examDetails->average_points;
         $students[$s]->studentDev = $this->calculateStudentDeviation($students[$s]->examDetails, $students[$s]->student_id);
         $students[$s]->classTeacher = $this->getClassTeacher($students[$s]->examDetails);
         $students[$s]->classEntries = $this->getClassEntries($exam_id, $students[$s]->section_id);
         $students[$s]->otherExams = $this->fetchStudentAnalysedExams($students[$s]->student_id, $exam_id);
      }

      if ($report_type == 1) {
          usort($students, array($this, 'sortStudentsByPoints'));
      } else {
          usort($students, array($this, 'sortStudentsByDeviation'));
      }
      return $students;
   }

   // get specific student subject scores in a given exam
   private function getStudentSubjectsScores($student_id, $exam_id)
   {
      $exam = Exam::find($exam_id);
      $student = Student::find($student_id);
      $schoolSubjects = $this->getSchoolSubjects();
      $subjectScores = $this->fetchStudentSubjectScores($student_id, $exam_id);
      
      for ($sub=0; $sub <count($schoolSubjects) ; $sub++) 
      { 
         $subjectGrading = new SubjectGrading;
         $schoolSubjects[$sub]->subjectScore = '--';
         $schoolSubjects[$sub]->subjectGrade = '';
         $schoolSubjects[$sub]->subjectPoints = '';
         $schoolSubjects[$sub]->subjectPosition = '';
         $schoolSubjects[$sub]->score_id = '';
         
         for ($score=0; $score <count($subjectScores) ; $score++) 
         { 
            $studentsClassSubjectScores = $this->fetchStudentsSubjectScores($subjectScores[$score]->subject_id, $exam_id, null);
            $studentsSectionSubjectScores = $this->fetchStudentsSubjectScores($subjectScores[$score]->subject_id, $exam_id, $student->section_id);
            if ($schoolSubjects[$sub]->subject_id == $subjectScores[$score]->subject_id) 
            {
                $schoolSubjects[$sub]->score_id = $subjectScores[$score]->score_id; 
                $schoolSubjects[$sub]->subjectScore = $subjectScores[$score]->score; 
                $schoolSubjects[$sub]->subjectGrade = $subjectGrading->getSubjectGrading($subjectScores[$score]->score, $subjectScores[$score]->subject_id, $exam->class_numeric)['grade_name'];
                $schoolSubjects[$sub]->subjectPoints  = $subjectGrading->getSubjectPoints($schoolSubjects[$sub]->subjectGrade);
                $schoolSubjects[$sub]->classPosition  = $this->getStudentSubjectPosition($studentsClassSubjectScores, $subjectScores[$score]->score_id, count($studentsClassSubjectScores));
                $schoolSubjects[$sub]->sectionPosition  = $this->getStudentSubjectPosition($studentsSectionSubjectScores, $subjectScores[$score]->score_id, count($studentsSectionSubjectScores));
                
                //Save analysed subject score
                $this->saveAnalysedSubjectScore($exam_id, $subjectScores[$score]->score_id, $schoolSubjects[$sub]);
            }
         }
      }
      return $schoolSubjects;
   }

   // get student anaylysed subject scores in school subjects
   private function getStudentAnalysedSubjectsScores($student_id, $exam_id)
   {
      $exam = Exam::find($exam_id);
      $subjectGrading = new SubjectGrading();
      $subjectTeacher = new SubjectTeacher();
      $schoolSubjects = $this->getSchoolSubjects();
      $subjectScores = $this->fetchStudentAnalysedSubjectScores($student_id, $exam_id);

      for ($sub=0; $sub <count($schoolSubjects) ; $sub++) 
      { 
         $schoolSubjects[$sub]->subjectScore = '--';
         $schoolSubjects[$sub]->subjectGrade = '';
         $schoolSubjects[$sub]->subjectSectionPosition = '';
         $schoolSubjects[$sub]->subjectClassPosition = '';
         $schoolSubjects[$sub]->subjectRemarks = '';
         $schoolSubjects[$sub]->subjectTeacher = '';

         for ($score=0; $score <count($subjectScores) ; $score++) 
         { 
            if ($schoolSubjects[$sub]->subject_id == $subjectScores[$score]->subject_id) 
            {
               $teacher = $subjectTeacher->getSubjectTeacherBySectionId($subjectScores[$score]->subject_id, $subjectScores[$score]->section_id);
               $schoolSubjects[$sub]->subjectScore = $subjectScores[$score]->score;
               $schoolSubjects[$sub]->subjectGrade = $subjectScores[$score]->subject_grade;
               $schoolSubjects[$sub]->subjectSectionPosition = $subjectScores[$score]->section_position;
               $schoolSubjects[$sub]->subjectClassPosition = $subjectScores[$score]->class_position;
               $schoolSubjects[$sub]->subjectTeacher = is_null($teacher) ? '' : $teacher->name_initial;
               $schoolSubjects[$sub]->subjectRemarks = $subjectGrading->getSubjectGrading(
                  $subjectScores[$score]->score, 
                  $schoolSubjects[$sub]->subject_id, 
                  $exam->class_numeric)['score_remarks'];
            }
         }
      }
      return $schoolSubjects;
   }

   //save analysed subject score
   private function saveAnalysedSubjectScore($exam_id, $score_id, $subject)
   {
       DB::table('students_analysed_scores')->where('score_id', $score_id)->delete();
       return DB::table('students_analysed_scores')->insert([
         'exam_id' =>  $exam_id,
         'score_id' =>  $score_id,
         'subject_grade' =>  $subject->subjectGrade,
         'section_position' => $subject->sectionPosition,
         'class_position' => $subject->classPosition,
         'created_at' => Carbon::now(),
         'updated_at' => Carbon::now(),
       ]);
   }

   //save analysed student exam
   private function saveAnalysedStudentExam($students, $exam_id, $section_id, $class_entry)
   {
      DB::table('students_analysed_exams')->where(['exam_id' => $exam_id, 'section_id' => $section_id])->delete();
      for ($s=0; $s <count($students) ; $s++) { 
         DB::table('students_analysed_exams')->insert([
                  'exam_id' =>  $exam_id,
                  'section_id' =>  $section_id,
                  'student_id' =>  $students[$s]->student_id,
                  'subjects_entry' =>  $students[$s]->subjectsEntry,
                  'total_points' => $students[$s]->points->totalPoints,
                  'average_points' => $students[$s]->points->averagePoints,
                  'average_grade' => $students[$s]->points->averageGrade,
                  'section_position' => $students[$s]->classPosition,
                  'section_entry' => $class_entry,
                  'class_entry' => $class_entry,
                  'created_at' => Carbon::now(),
                  'updated_at' => Carbon::now(),
               ]);
      }
      return true;
   }

   //save analysed grades distribution
   private function saveAnalysedGradesDistribution($gradesData, $exam_id, $section_id, $students_entry)
   {
      DB::table('grades_distribution')->where(['exam_id' => $exam_id, 'section_id' => $section_id])->delete();
      
      return GradesDistribution::create([
         'exam_id' => $exam_id,
         'section_id' => $section_id,
         'students_entry' => $students_entry,
         'total_points' =>  $gradesData['totalPoints' ],
         'average_grade' =>  $gradesData['averageGrade' ],
         'total_students' => $gradesData['totalStudents' ],
         'average_points' =>  $gradesData['averagePoints' ],
         'grades' => $this->getCleanGradesDistribution($gradesData['grades']),
      ]);
   }

   // save graph data
   private function saveGraphData($graphData, $exam_id, $section_id)
   {
      DB::table('graph_data')->where(['exam_id' => $exam_id, 'section_id' => $section_id])->delete();
       return GraphData::create([
         'exam_id' => $exam_id,
         'section_id' => $section_id,
         'subjects' => $graphData->subjects,
         'mean_scores' => $graphData->meanScores,
      ]);
   }

   //save analysed grades distribution
   private function saveSubjectsAnalysis($subjects, $exam_id, $section_id)
   {
      DB::table('subjects_analysis')->where(['exam_id' => $exam_id, 'section_id' => $section_id])->delete();
      
      for ($sub=0; $sub <count($subjects) ; $sub++) { 
       SubjectAnalysis::create([
         'exam_id' => $exam_id,
         'section_id' => $section_id,
         'subject_id' => $subjects[$sub]->subject_id,
         'total_points' =>  $subjects[$sub]->subjectTotalPoints,
         'average_grade' =>  $subjects[$sub]->subjectGrade,
         'total_students' => $subjects[$sub]->subjectEntry,
         'class_position' => $subjects[$sub]->subjectPosition,
         'average_points' => $subjects[$sub]->subjectPoints,
         'total_marks' => $subjects[$sub]->subjectTotalMarks,
         'average_marks' => $subjects[$sub]->subjectMeanMarks,
         'grades' => $this->getCleanGradesDistribution($subjects[$sub]->grades),
      ]);
      }

      return true;
   }

   //get clean grades distributions
   private function getCleanGradesDistribution($gradesData)
   {
      $grades = DB::table('default_grades')->select('grade_name')->get()->toArray();
      for ($g=0; $g <count($grades) ; $g++) { 
         for ($i=0; $i <count($gradesData) ; $i++) 
         { 
           if ($grades[$g]->grade_name == $gradesData[$i]['grade_name']) {
              $grades[$g]->totalGrades = $gradesData[$i]['totalGrades'];
           }
         }
      }
      return $grades;
   }

   //save student overall position
   private function saveStudentsOverallPosition($students, $exam_id)
   {
      $class_entry = count($students);
      for ($s=0; $s <count($students) ; $s++) { 
         DB::table('students_analysed_exams')
            ->where(['student_id' => $students[$s]->student_id, 'exam_id' => $exam_id])
            ->update([
               'class_position' => $students[$s]->classPosition,
               'class_entry' => $class_entry,
            ]);
      }
      return true;
   }

   // fetch from the database specific student subject scores in a given exam
   private function fetchStudentSubjectScores($student_id, $exam_id)
   {
      return DB::table('scores')->where(['student_id' => $student_id, 'exam_id' => $exam_id])->get();
   }

   // fetch from the database specific student analysed subject scores in a given exam
   private function fetchStudentAnalysedSubjectScores($student_id, $exam_id)
   {
      return DB::table('students_analysed_scores')
               ->join('scores' , 'scores.score_id', '=', 'students_analysed_scores.score_id')
               ->select(
                  'students_analysed_scores.*',
                  'exam_record_id',
                  'class_numeric',
                  'student_id',
                  'section_id',
                  'subject_id',
                  'score'
               )
               ->where(['student_id' => $student_id, 'scores.exam_id' => $exam_id])
               ->get();
   }

   // fetch from the database students subject scores in a given exam
   private function fetchStudentsSubjectScores($subject_id, $exam_id, $section_id)
   {
      if (is_null($section_id)) {
          return DB::table('scores')
                   ->select('score_id', 'score')
                   ->where(['subject_id' => $subject_id, 'exam_id' => $exam_id])
                   //->orderBy('score', 'desc')
                   ->get()->toArray();
      }
      return DB::table('scores')
               ->select('score_id', 'score')
               ->where(['subject_id' => $subject_id,'section_id' => $section_id, 'exam_id' => $exam_id])
               //->orderBy('score', 'desc')
               ->get()->toArray();
   }

   //fetch student analysed exam details
   private function fetchStudentAnalysedExamDetails($student_id, $exam_id)
   {
      return DB::table('students_analysed_exams')
               //->select('id', 'exam_id', 'student_id', 'subjects_entry', 'total_points', 'average_points', 'average_grade', 'section_position', 'class_position')
               ->where(['student_id' => $student_id, 'exam_id' => $exam_id])
               ->first();
   }

   //fetch student analysed exams
   public function fetchStudentAnalysedExams($student_id, $exam_id)
   {
      return DB::table('students_analysed_exams')
               ->select(
                  'id',
                  'name',
                  'year',
                  'term', 
                  'subjects_entry',
                  'total_points',
                  'average_points',
                  'average_grade',
                  'section_position',
                  'class_position',
                  'class_entry',
                  'section_entry'
                  )
               ->join('exams', 'exams.exam_id', '=', 'students_analysed_exams.exam_id')
               ->where('student_id', $student_id)
               //->whereNotIn('exam_id', [$exam_id])
               ->orderBy('id', 'asc')
               ->get();
   }

   // get all students single exam analysed scores
   public function getStudentsAnalysedExamScores($exam_id)
   {
      $students = $this->fetchStudentsAnalysedExams($exam_id);
      for ($s=0; $s <count($students) ; $s++) { 
         $students[$s]->examData = $this->getStudentAnalysedExamScores($students[$s]->student_id, $exam_id);
      }
      return $students;
   }

   //fetch all student analysed exams
   private function fetchStudentsAnalysedExams($exam_id)
   {
      return DB::table('students_analysed_exams')
               ->select(
                  'name',
                  'section_name',
                  'kcpe_marks',
                  'section_numeric',
                  'admission_no', 
                  'class_position',
                  'students.student_id',
                  )
               ->join('students', 'students.student_id', '=', 'students_analysed_exams.student_id')
               ->join('sections', 'sections.section_id', '=', 'students.section_id')
               ->join('users', 'users.id', '=', 'students.student_user_id')
               ->where('exam_id', $exam_id)
               ->orderBy('class_position', 'asc')
               ->get();
   }

   //calculate student deviation
   private function calculateStudentDeviation($examDetails, $student_id)
   {
      $dev = 0;
      $currentPoints = $examDetails->average_points;
      $previousPoints = $this->getStudentPreviousExamPoints($examDetails->id, $student_id);
      $dev = $currentPoints - $previousPoints;
      return $dev;
   }

   //private function get student previous exam point
   private function getStudentPreviousExamPoints($current_id, $student_id)
   {
      $points = 0;
      $exams = DB::table('students_analysed_exams')->where('student_id', $student_id)->whereNotIn('id', [$current_id])->orderBy('id', 'DESC')->get();
      if (count($exams) !== 0) $points = $exams[0]->average_points;
      return $points;
   }

   //private function get previous exam point
   private function getSubjectExamPreviousPoints($current_id, $section_id, $subject_id)
   {
      $points = 0;
      $subjects = DB::table('subjects_analysis')->where(['subject_id' => $subject_id, 'section_id' => $section_id])->whereNotIn('id', [$current_id])->orderBy('id', 'DESC')->get();
      if (count($subjects) !== 0) $points = $subjects[0]->average_points;
      return $points;
   }

   //get student subjects entry
   private function getStudentSubjectEntry($scores)
   {
      return count($scores);
   }

   //Calculate total points
   private function calculateTotalPoints($subjectScores, $exam_id)
   {
       $totalPoints = 0;
       $subjectsCount = 0;
       $points = new stdClass;
       $exam = Exam::find($exam_id);
       $subjectGrading = new SubjectGrading;
       $overallGrading = new OverallGrading();
       $schoolSubjects = $this->getSchoolSubjects();
       $form = Form::where('form_numeric', $exam->class_numeric)->first(); 
       $compulsorySubjects = $this->getSubjectsByOptionality('Compulsory');
       $studentCompulsory = $this->getStudentSubjectScoresOptionality($subjectScores)['compulsory'];

       if(
         count($subjectScores) == 0 
         || is_null($subjectScores) 
         || $subjectScores->count() < $form->min_subs
         || count($studentCompulsory) < count($compulsorySubjects)
       ){
          $points->averagePoints = '0';
          $points->totalPoints = '0';
          $points->averageGrade = '--';
          return $points;
       }

       for ($s=0; $s <count($subjectScores) ; $s++) 
       { 
         $subjectScores[$s]->subjectScore = $subjectScores[$s]->score; 
         $subjectScores[$s]->subjectGrade = $subjectGrading->getSubjectGrading($subjectScores[$s]->subjectScore, $subjectScores[$s]->subject_id, $exam->class_numeric)['grade_name'];
         $subjectScores[$s]->subjectPoints = $subjectGrading->getSubjectPoints($subjectScores[$s]->subjectGrade);
       }

       switch ($exam->class_numeric) 
       {
          case 1:
          case 2:
                for ($s=0; $s <count($subjectScores) ; $s++) { 
                    $totalPoints += $subjectScores[$s]->subjectPoints;
                    $subjectsCount = $subjectsCount + 1;
                }
          break;

          case 3:
          case 4:
               $sciences = array();
               $humanities = array();
               $technicals = array();
               for ($sub=0; $sub <count($schoolSubjects) ; $sub++) 
               { 
                  for ($score=0; $score <count($subjectScores) ; $score++) 
                  { 
                    
                     ///add points for maths to total points
                     if ($subjectScores[$score]->subject_id == $schoolSubjects[$sub]->subject_id 
                         && $schoolSubjects[$sub]->group == 'Mathematics' ) {
                         $totalPoints += $subjectScores[$score]->subjectPoints;
                         $subjectsCount = $subjectsCount + 1;
                         break;
                     }

                     ///add points for languages to total points
                     if ($subjectScores[$score]->subject_id == $schoolSubjects[$sub]->subject_id 
                         && $schoolSubjects[$sub]->group == 'Languages' ) {
                         $totalPoints += $subjectScores[$score]->subjectPoints;
                         $subjectsCount = $subjectsCount + 1;
                         break;
                     }

                     ///create an array for Sciences
                     if ($subjectScores[$score]->subject_id == $schoolSubjects[$sub]->subject_id 
                         && $schoolSubjects[$sub]->group == 'Sciences' ) {
                         array_push($sciences, $subjectScores[$score]);
                         break;
                     }

                     ///create an array for Humanities
                     if ($subjectScores[$score]->subject_id == $schoolSubjects[$sub]->subject_id 
                         && $schoolSubjects[$sub]->group == 'Humanities' ) {
                         array_push($humanities, $subjectScores[$score]);
                         break;
                     }

                       ///create an array for Technicals
                     if ($subjectScores[$score]->subject_id == $schoolSubjects[$sub]->subject_id 
                         && $schoolSubjects[$sub]->group == 'Technicals' ) {
                         array_push($technicals, $subjectScores[$score]);
                         break;
                     }
                  }//end for loop : SCORES
               }//end for loop : SCHOOL SUBJECTS

                //add  two best sciences to the total points
                usort($sciences, 'static::sortSubjectsByPoints');
                if (count($sciences)>=2) 
                {
                  $totalPoints += $sciences[0]->subjectPoints + $sciences[1]->subjectPoints;
                  $subjectsCount = $subjectsCount + 2;
                }

                //add the best humanities to the total points
                usort($humanities, 'static::sortSubjectsByPoints');
                if (count($humanities)>=1) 
                {
                  $totalPoints += $humanities[0]->subjectPoints;
                  $subjectsCount = $subjectsCount + 1;
                }

                 //add the best technicals to the total points
                usort($technicals, 'static::sortSubjectsByPoints');
                if (count($technicals)>=1) 
                {
                  $totalPoints += $technicals[0]->subjectPoints;
                  $subjectsCount = $subjectsCount + 1;
                }
                break;
       }

       $points->totalPoints = $totalPoints;
       //$points->compulsorySubs =  count($compulsorySubjects );
       $points->averagePoints = number_format(round($totalPoints/$subjectsCount, 2), 2);
       $points->averageGrade = $overallGrading->getOverallGrading($points->totalPoints, $exam->class_numeric)['grade_name'];
       return $points;
   }

   //Calculate student total marks
   private function calculateTotalMarks($subjectScores, $classNumeric)
   {
       $form = Form::where('form_numeric', $classNumeric)->first(); 
       if(is_null($subjectScores) || $subjectScores->count() < $form->min_subs)
       return 'Z';

       $totalMarks = 0;
       for ($s=0; $s <count($subjectScores) ; $s++) { 
          $totalMarks += $subjectScores[$s]->scores;
       }
       return $totalMarks;
   }

   //.Assign student position 
   private function assignStudentPosition($student_id, $students, $minSubjects)
   {
      usort($students, array($this, 'sortStudentsByPoints'));
      for ($s=0; $s <count($students) ; $s++) 
      { 
         $students[$s]->classPosition = 'Z';
         if ($students[$s]->subjectsEntry >= $minSubjects) 
         {
            $students[$s]->classPosition = $s + 1;
            if ($s > 0) 
            {
               $previousIndex = $s - 1;
               if ($students[$s]->points->totalPoints == $students[$previousIndex]->points->totalPoints)
               {
                  $students[$s]->classPosition = $students[$previousIndex]->classPosition;
               }
               else
               {
                  $students[$s]->classPosition = $s + 1;
               }
            }
            
            if ($students[$s]->student_id == $student_id) 
            {
              return  $students[$s]->classPosition;
            }
         }
      }
   }

   //.Assign student position 
   private function assignStudentPositionByDeviation($student_id, $students, $minSubjects)
   {
      usort($students, array($this, 'sortStudentsByPoints'));
      for ($s=0; $s <count($students) ; $s++) 
      { 
         $students[$s]->classPosition = 'Z';
         if ($students[$s]->subjectsEntry >= $minSubjects) 
         {
            $students[$s]->classPosition = $s + 1;
            if ($s > 0) 
            {
               $previousIndex = $s - 1;
               if ($students[$s]->studentDev == $students[$previousIndex]->studentDev)
               {
                  $students[$s]->classPosition = $students[$previousIndex]->classPosition;
               }
               else
               {
                  $students[$s]->classPosition = $s + 1;
               }
            }
            
            if ($students[$s]->student_id == $student_id) 
            {
              return  $students[$s]->classPosition;
            }
         }
      }
   }
   
   //. Get grades distribution 
   private function getGradeDistribution($students)
   {
      //print_r($students);
      $gradesData = [
         'grades' => [],
         'totalStudents' => 0,
         'totalPoints' => 0,
         'averagePoints' => 0,
         'averageGrade' => ''
      ];
      $defaultGrades = new DefaultGrade();
      $subjectGrading = new SubjectGrading();
      $grades = $defaultGrades->getDefaultGrades();
      $count = 0;
      $totalStudents = 0;
      $totalPoints = 0;
      $gradesPoint = 0;
      $averagePoints = 0;

      for ($g=0; $g <count($grades) ; $g++) 
      { 
         $grades[$g]['totalGrades'] = 0;
         for ($s=0; $s <count($students) ; $s++) 
         { 
            if ($grades[$g]['grade_name'] == $students[$s]->points->averageGrade) 
            {
               $grades[$g]['totalGrades'] = $grades[$g]['totalGrades'] + 1;
               $totalStudents = $totalStudents + 1;
               $gradesPoint = $gradesPoint + $students[$s]->points->averagePoints;
            }
         }
      }

      $count = $count + $totalStudents;
      $totalPoints = $totalPoints + $gradesPoint;
     
      if ($count > 0) 
      $averagePoints = number_format(round($totalPoints/$count, 2), 2);
      $averageGrade =  $subjectGrading->getStudentMeanGrade($averagePoints);
      
      $gradesData = [
         'grades' => $grades,
         'totalStudents' => $count,
         'totalPoints' => $totalPoints,
         'averagePoints' => $averagePoints,
         'averageGrade' => $averageGrade
      ];
      return $gradesData;
   }

   // get analysed grades distribution
   private function getAnalysedGradesDistribution($section_id, $exam_id)
   {
      return DB::table('grades_distribution')->where(['exam_id' => $exam_id, 'section_id' => $section_id])->first();
   }

   // get analysed graph data
   private function getAnalysedGraphData($section_id, $exam_id)
   {
      return DB::table('graph_data')->where(['exam_id' => $exam_id, 'section_id' => $section_id])->first();
   }

   // get class analysed grades distribution
   private function getAnalysedClassSubjects($section_id, $exam_id, $report_type)
   {
      $subjects = DB::table('subjects_analysis')
                    ->select('subjects_analysis.*', 'subjects.subject_name')
                    ->where(['exam_id' => $exam_id, 'section_id' => $section_id])
                    ->join('subjects', 'subjects.subject_id', '=', 'subjects_analysis.subject_id')
                    ->get()->toArray();
      for ($s=0; $s <count($subjects) ; $s++) { 
         $subjects[$s]->classPosition = $this->getAnalysedSubjectPosition($subjects[$s]->subject_id, $exam_id, null)->class_position;
         $subjects[$s]->sectionPosition = $this->getAnalysedSubjectPosition($subjects[$s]->subject_id, $exam_id, $section_id)->class_position;
         $subjects[$s]->subjectDev = $this->calculateSubjectDeviation($subjects[$s], $exam_id, $section_id);
      }
      
      if ($report_type == 6) 
      usort($subjects, array($this, 'sortSubjectsByDeviation'));
      return $subjects;
   }

   //calculate student deviation
   private function calculateSubjectDeviation($subject, $exam_id, $section_id)
   {
      $dev = 0;
      $currentPoints = $subject->average_points;
      $previousPoints = $this->getSubjectExamPreviousPoints($subject->id, $section_id, $subject->subject_id);
      $dev = $currentPoints - $previousPoints;
      return $dev;
   }

   //get subject analysed positions
   private function getAnalysedSubjectPosition($subject_id, $exam_id, $section_id)
   {
      return DB::table('subjects_analysis')->select('class_position')->where([
         'exam_id' => $exam_id, 
         'subject_id' => $subject_id, 
         'section_id' => $section_id]
         )->first();
   }

   //. Get class exam subjects anaylisis
   private function getSubjectsAnalysis($students,  $form_numeric)
   {    
      // print_r($students); 
       $subjects = $this->getSchoolSubjects();
       $defaultGrades = new DefaultGrade();
       $subjectGrading = new SubjectGrading();
       $grades = $defaultGrades->defaultGrades();

       for ($s=0; $s <count($subjects) ; $s++)
       { 
         $subjects[$s]->subjectEntry = 0; 
         $subjects[$s]->subjectPosition = 0; 
         $subjects[$s]->subjectTotalPoints = 0; 
         $subjects[$s]->subjectPoints = 0;
         $subjects[$s]->subjectTotalMarks = 0;  
         $subjects[$s]->subjectMeanMarks = 0; 
         $subjects[$s]->subjectGrade = '--'; 
         $subjects[$s]->grades = $grades;

         for ($g=0; $g <count($subjects[$s]->grades) ; $g++) 
         { 
            $subjects[$s]->grades[$g]['totalGrades'] = 0;
            for ($stud=0; $stud <count($students) ; $stud++)
            { 
               for ($score=0; $score <count($students[$stud]->subjectScores); $score++) 
               { 
                  if ($students[$stud]->subjectScores[$score]->subjectGrade == $subjects[$s]->grades[$g]['grade_name'] 
                  && $students[$stud]->subjectScores[$score]->subject_id == $subjects[$s]->subject_id) 
                  {
                    $subjects[$s]->grades[$g]['totalGrades'] = $subjects[$s]->grades[$g]['totalGrades'] + 1;
                    $subjects[$s]->subjectEntry = $subjects[$s]->subjectEntry + 1;
                    $subjects[$s]->subjectTotalPoints = $subjects[$s]->subjectTotalPoints + $students[$stud]->subjectScores[$score]->subjectPoints;
                    $subjects[$s]->subjectTotalMarks = $subjects[$s]->subjectTotalMarks + $students[$stud]->subjectScores[$score]->subjectScore;
                    break;
                  }
               }
            }

           if ($subjects[$s]->subjectEntry > 0)
           {
              $subjects[$s]->subjectPoints = number_format(round(($subjects[$s]->subjectTotalPoints/$subjects[$s]->subjectEntry), 2), 2);
              $subjects[$s]->subjectMeanMarks = number_format(round(($subjects[$s]->subjectTotalMarks/$subjects[$s]->subjectEntry), 2), 2);
              $subjects[$s]->subjectGrade =  $subjectGrading->getStudentMeanGrade($subjects[$s]->subjectPoints);
              //$subjects[$s]->subjectGrade =  $subjectGrading->getStudentMeanGrade($subjects[$s]->subjectMeanMarks);
           } 
         }
       }

      //Loop sorted subjects to assign subjects positions
      usort($subjects, array($this, 'sortSubjectsByPoints'));
      for ($s=0; $s <count($subjects) ; $s++) 
      { 
         $subjects[$s]->subjectPosition = $this->assignSubjectsPosition($subjects[$s]->subject_id, $subjects);
      }
      return $subjects;
   }

   //get subject total grades
   private function getSubjectTotalGradeEntries($studentScores, $subject_id, $grade, $totalGrades)
   {
      for ($s=0; $s <count($studentScores) ; $s++) 
      { 
         if ($studentScores[$s]->subject_id == $subject_id && $studentScores[$s]->subjectGrade == $grade) {
            $totalGrades = $totalGrades + 1;
         }
      }
      return $totalGrades; 
   }

   //.Assign subjects positions
   private function assignSubjectsPosition($subject_id, $subjects)
   {
      for ($s=0; $s <count($subjects) ; $s++) 
      { 
         $subjects[$s]->position = $s + 1;
         if ($s > 0) 
         {
            $previousIndex = $s - 1;
            if ($subjects[$s]->subjectPoints == $subjects[$previousIndex]->subjectPoints)
            {
               $subjects[$s]->position = $subjects[$previousIndex]->position;
            }
            else
            {
               $subjects[$s]->position = $s + 1;
            }
         }

         if ($subjects[$s]->subject_id == $subject_id) 
         return  $subjects[$s]->position;
      }
   }

    //.Assign subjects positions
   private function assignStreamPosition($section_id, $sections)
   {
      for ($s=0; $s <count($sections) ; $s++) 
      { 
         $sections[$s]->position = $s + 1;
         if ($s > 0) 
         {
            $previousIndex = $s - 1;
            if ($sections[$s]->average_points == $sections[$previousIndex]->average_points)
            {
               $sections[$s]->position = $sections[$previousIndex]->position;
            }
            else
            {
               $sections[$s]->position = $s + 1;
            }
         }

         if ($sections[$s]->section_id == $section_id) 
         return  $sections[$s]->position;
      }
   }

   //.Assign subjects positions
   private function assignStreamPositionByDeviation($section_id, $sections)
   {
      for ($s=0; $s <count($sections) ; $s++) 
      { 
         $sections[$s]->position = $s + 1;
         if ($s > 0) 
         {
            $previousIndex = $s - 1;
            if ($sections[$s]->examDev == $sections[$previousIndex]->examDev)
            {
               $sections[$s]->position = $sections[$previousIndex]->position;
            }
            else
            {
               $sections[$s]->position = $s + 1;
            }
         }

         if ($sections[$s]->section_id == $section_id) 
         return  $sections[$s]->position;
      }
   }

   // get studebt subject scores optionality
   private function getStudentSubjectScoresOptionality($subjectScores)
   {
      $optionals = [];
      $compulsory = [];
      $schoolSubjects = $this->getSchoolSubjects();


      for ($sub=0; $sub <count($schoolSubjects) ; $sub++) { 
        
         for ($score=0; $score <count($subjectScores) ; $score++) 
         { 
            if ($schoolSubjects[$sub]->subject_id == $subjectScores[$score]->subject_id && $schoolSubjects[$sub]->optionality == 'Compulsory') 
            {
               array_push($compulsory, $subjectScores[$score]->subject_id);
               break;
            }
            if ($schoolSubjects[$sub]->subject_id == $subjectScores[$score]->subject_id && $schoolSubjects[$sub]->optionality == 'Optional') {
               array_push($optionals, $subjectScores[$score]->subject_id);
               break;
            }
         }
      }
      return [
         'compulsory' => $compulsory,
         'optionals' => $optionals
      ];
   }

   // get student subject position
   private function getStudentSubjectPosition($scores, $score_id, $subjectEntry)
   {
      usort($scores, array($this, 'sortSubjectsByScores'));
      // return $subjectEntry;
      for ($s=0; $s <count($scores) ; $s++) 
      { 
         $scores[$s]->position = $s + 1;
         if ($s > 0) 
         {
            $previousIndex = $s - 1;
            if ($scores[$s]->score == $scores[$previousIndex]->score)
            {
               $scores[$s]->position = $scores[$previousIndex]->position;
            }
            else
            {
               $scores[$s]->position = $s + 1;
            }
         }

         if ($scores[$s]->score_id == $score_id) 
         return  $scores[$s]->position.'/'.$subjectEntry;
      }
      //return $scores;
   }

   // Sort subjects by points
   public static function sortSubjectsByPoints($sub_a, $sub_b)
   {
      if($sub_a->subjectPoints == $sub_b->subjectPoints)
       return 0;
      if($sub_a->subjectPoints < $sub_b->subjectPoints)
       return 1;
      if($sub_a->subjectPoints > $sub_b->subjectPoints)
       return -1;
   }

   // Sort subjects by score
   public static function sortSubjectsByScores($sub_a, $sub_b)
   {
      if($sub_a->score == $sub_b->score)
       return 0;
      if($sub_a->score < $sub_b->score)
       return 1;
      if($sub_a->score > $sub_b->score)
       return -1;
   }

   // Sort subjects by deviation
   public static function sortSubjectsByDeviation($sub_a, $sub_b)
   {
      if($sub_a->subjectDev == $sub_b->subjectDev)
       return 0;
      if($sub_a->subjectDev < $sub_b->subjectDev)
       return 1;
      if($sub_a->subjectDev > $sub_b->subjectDev)
       return -1;
   }

   // Sort students by points
   public static function sortStudentsByPoints($student_a, $student_b)
   {
     // return strcmp($student_a['averagePoints'], $student_b['averagePoints']);
      if($student_a->averagePoints == $student_b->averagePoints)
       return 0;
      if($student_a->averagePoints < $student_b->averagePoints)
       return 1;
      if($student_a->averagePoints > $student_b->averagePoints)
       return -1;
   }

   // Sort students by student deviation
   public static function sortStudentsByDeviation($student_a, $student_b)
   {
      if($student_a->studentDev == $student_b->studentDev)
       return 0;
      if($student_a->studentDev < $student_b->studentDev)
       return 1;
      if($student_a->studentDev > $student_b->studentDev)
       return -1;
   }

   // Sort streams by points
   public static function sortStreamsByPoints($stream_a, $stream_b)
   {
      if($stream_a->average_points == $stream_b->average_points)
       return 0;
      if($stream_a->average_points < $stream_b->average_points)
       return 1;
      if($stream_a->average_points > $stream_b->average_points)
       return -1;
   }

   // Sort exam by deviation
   public static function sortByExamDeviation($stream_a, $stream_b)
   {
      if($stream_a->examDev == $stream_b->examDev)
       return 0;
      if($stream_a->examDev < $stream_b->examDev)
       return 1;
      if($stream_a->examDev > $stream_b->examDev)
       return -1;
   }

   // calculate class deviation
   private function calculateClassDeviation($gradesData, $section_id)
   {
      $dev = 0;
      $currentPoints = $gradesData->average_points;
      $previousPoints = $this->getClassExamPreviousPoints($gradesData->id, $section_id);
      $dev = $currentPoints - $previousPoints;
      return $dev;
   }

   //private function get previous class point
   private function getClassExamPreviousPoints($current_id, $section_id)
   {
      $points = 0;
      $exams = DB::table('grades_distribution')->where(['section_id' => $section_id])->whereNotIn('id', [$current_id])->orderBy('id', 'DESC')->get();
      if (count($exams) !== 0) $points = $exams[0]->average_points;
      return $points;
   }

   //get stream ranking details
   public function getStreamsRankingDetails($exam_id, $report_type)
   {
      $streams = $this->getStreamsRanking($exam_id);
      for ($s=0; $s <count($streams) ; $s++) { 
         $gradesData = $this->getAnalysedGradesDistribution($streams[$s]->section_id, $exam_id);
         $streams[$s]->examDev = $this->calculateClassDeviation($gradesData, $exam_id);
         $streams[$s]->position = $this->assignStreamPosition($streams[$s]->section_id, $streams);
         if ($report_type == 9) 
         $streams[$s]->position = $this->assignStreamPositionByDeviation($streams[$s]->section_id, $streams);
      }
      if ($report_type == 9) {
         usort($streams, array($this, 'sortByExamDeviation'));
      } else {
          usort($streams, array($this, 'sortStreamsByPoints'));
      }
      return $streams;
   }

   // get streams ranking
   public function getStreamsRanking($exam_id)
   {
      return DB::table('grades_distribution')
               ->join('sections', 'sections.section_id', '=', 'grades_distribution.section_id')
               ->select('grades_distribution.*', 'sections.section_name', 'sections.section_numeric')
               ->where(['exam_id' => $exam_id])
              // ->orderBy('average_points', 'desc')
               ->get()->toArray();
   }

   // get class teacher
   private function getClassTeacher($examDetails)
   {
      $classTeacher = [
         'teacher' => '',
         'teacher_remarks' => '',
         'principal_remarks' => ''
      ];

      $grading = new OverallGrading();
      $exam = Exam::find($examDetails->exam_id);
      $section_id = Student::find($examDetails->student_id)->section_id;
      $teacher = Section::getSectionTeacher($section_id);
      $remarks = $grading->getOverallGrading($examDetails->total_points, $exam->class_numeric);
      
      $classTeacher = [
         'teacher' => $teacher,
         'teacher_remarks' => $remarks['teacher_remarks'],
         'principal_remarks' => $remarks['principal_remarks']
      ];
      return $classTeacher;
   }

   //get class entry
   private function getClassEntries($exam_id, $section_id)
   {
      $classEntries = [
         'sectionEntry' => $this->getAnalysedGradesDistribution($section_id, $exam_id)->students_entry,
         'classEntry' => $this->getAnalysedGradesDistribution(null, $exam_id)->students_entry
      ];
      return $classEntries;
   }

    //. Get subjects anaylysis graph data 
   public function getSubjectsAnalysisGraphData($subjectsData)
   {
       $graphData = new stdClass; 
       $subjectsMeanPoints = [];
       $subjectsArray = [];
       //$subjects = $this->getSchoolSubjects();
   
       for ($s=0; $s <count($subjectsData) ; $s++) 
       { 
           array_push($subjectsMeanPoints, floatval($subjectsData[$s]->subjectPoints));
           array_push($subjectsArray, $subjectsData[$s]->subject_short);
       }

       $graphData->subjects = $subjectsArray;
       $graphData->meanScores = json_encode($subjectsMeanPoints);
       return $graphData;
   }

}
