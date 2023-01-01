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
      $this->getStudentsScores($students, $exam_id);
      usort($students, array($this, 'sortStudentsByPoints'));

      //Save students overall class position
      $this->saveStudentsOverallPosition($students, $exam_id);

      return [
         'students' => $students,
         'gradesData' => $this->getGradeDistribution($students),
         'subjects' => $this->getSubjectsAnalysis($students, $section_numeric)
      ];
   }

   //get class analysed students single exam results
   public function fetchClassAnalysedExamResults($exam_id, $section_numeric)
   {
      $students = Student::getStudentsByClassNumeric($section_numeric)->toArray();
      $this->getStudentsAnalysedScores($students, $exam_id);
      usort($students, array($this, 'sortStudentsByPoints'));

      return [
         'students' => $students,
        // 'gradesData' => $this->getGradeDistribution($students),
         //'subjects' => $this->getSubjectsAnalysis($students, $section_numeric)
      ];
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

          //Save students analysed exam
          $this->saveAnalysedStudentExam($sections[$s]->students, $exam_id, $sections[$s]->section_id);
      }
      return $sections;
   }

   //Get section analysed single exam results
   public function fetchSectionsAnalysedExamResults($exam_id, $section_numeric)
   {
      $sections = Section::getSectionsByClassNumeric($section_numeric);
      for ($s=0; $s <count($sections) ; $s++) {
          $students = Student::getStudentsBySectionId($sections[$s]->section_id)->toArray();
          $sections[$s]->students = $this->getStudentsAnalysedScores($students, $exam_id);
          //$sections[$s]->gradesData = $this->getGradeDistribution($sections[$s]->students);
          //$sections[$s]->subjects = $this->getSubjectsAnalysis($sections[$s]->students, $section_numeric);
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

   // get students analysed exam scores
   private function getStudentsAnalysedScores($students, $exam_id)
   {
      for ($s=0; $s <count($students) ; $s++) 
      { 
         $students[$s]->subjectScores = $this->getStudentAnalysedSubjectsScores($students[$s]->student_id, $exam_id);
         $students[$s]->examDetails = $this->fetchStudentAnalysedExamDetails($students[$s]->student_id, $exam_id);
         $students[$s]->averagePoints = $students[$s]->examDetails->average_points;
         $students[$s]->studentDev = $this->calculateStudentDeviation($students[$s]->examDetails, $students[$s]->student_id);
      }
      usort($students, array($this, 'sortStudentsByPoints'));
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
         
         for ($score=0; $score <count($subjectScores) ; $score++) 
         { 
            $studentsClassSubjectScores = $this->fetchStudentsSubjectScores($subjectScores[$score]->subject_id, $exam_id, null);
            $studentsSectionSubjectScores = $this->fetchStudentsSubjectScores($subjectScores[$score]->subject_id, $exam_id, $student->section_id);
            if ($schoolSubjects[$sub]->subject_id == $subjectScores[$score]->subject_id) 
            {
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
      $schoolSubjects = $this->getSchoolSubjects();
      $subjectScores = $this->fetchStudentAnalysedSubjectScores($student_id, $exam_id);

      for ($sub=0; $sub <count($schoolSubjects) ; $sub++) 
      { 
         $schoolSubjects[$sub]->subjectScore = '--';
         $schoolSubjects[$sub]->subjectGrade = '';
         $schoolSubjects[$sub]->subjectSectionPosition = '';
         $schoolSubjects[$sub]->subjectClassPosition = '';

         for ($score=0; $score <count($subjectScores) ; $score++) 
         { 
            if ($schoolSubjects[$sub]->subject_id == $subjectScores[$score]->subject_id) 
            {
               $schoolSubjects[$sub]->subjectScore = $subjectScores[$score]->score;
               $schoolSubjects[$sub]->subjectGrade = $subjectScores[$score]->subject_grade;
               $schoolSubjects[$sub]->subjectSectionPosition = $subjectScores[$score]->section_position;
               $schoolSubjects[$sub]->subjectClassPosition = $subjectScores[$score]->class_position;
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
   private function saveAnalysedStudentExam($students, $exam_id, $section_id)
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
                  'created_at' => Carbon::now(),
                  'updated_at' => Carbon::now(),
               ]);
      }
      return true;
   }

   //save student overall position
   private function saveStudentsOverallPosition($students, $exam_id)
   {
      for ($s=0; $s <count($students) ; $s++) { 
         DB::table('students_analysed_exams')
            ->where(['student_id' => $students[$s]->student_id, 'exam_id' => $exam_id])
            ->update(['class_position' => $students[$s]->classPosition]);
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
                   ->get();
      }
      return DB::table('scores')
               ->select('score_id', 'score')
               ->where(['subject_id' => $subject_id,'section_id' => $section_id, 'exam_id' => $exam_id])
               ->get();
   }

   //fet student analysed exam details
   private function fetchStudentAnalysedExamDetails($student_id, $exam_id)
   {
      return DB::table('students_analysed_exams')
               ->select('id', 'subjects_entry', 'total_points', 'average_points', 'average_grade', 'section_position', 'class_position')
               ->where(['student_id' => $student_id, 'exam_id' => $exam_id])
               ->first();
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

}
