@extends('layouts.app.report')

@section('content')
    @foreach ($sections as $section)
        <main style="page-break-after: always;">
            <h6>SECTION PERFORMANCE / {{ strtoupper($section->section_numeric . $section->section_name) }} /
                {{ $exam->name }} /
                {{ $exam->year }}</h6>
            <div>
                @if (empty($section->students))
                    <p>Students list empty</p>
                @else
                    <div>
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr style="background-color: grey">
                                    <th>S.P</th>
                                    <th>ADM. NO</th>
                                    <th style="text-align: left">NAMES</th>
                                    <th>CLASS</th>
                                    @foreach ($subjects as $subject)
                                        <th>{{ $subject->subject_short }}</th>
                                    @endforeach
                                    <th>KCPE</th>
                                    <th>ENT</th>
                                    <th>TPS</th>
                                    <th>AVP</th>
                                    <th>AGG</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($section->students as $student)
                                    <tr>
                                        <td><strong>{{ $student->classPosition }}</strong></td>
                                        <td><strong>{{ $student->admission_no }}</strong></td>
                                        <td style="text-align: left">{{ $student->name }}</td>
                                        <td>{{ $student->section_numeric . $student->section_name }} </td>
                                        @foreach ($student->subjectScores as $score)
                                            <td style="text-align: center">
                                                {{ $score->subjectScore . $score->subjectGrade }}</td>
                                        @endforeach
                                        <td>{{ $student->kcpe_marks }}</td>
                                        <td><strong>{{ $student->subjectsEntry }}</strong></td>
                                        <td><strong>{{ $student->points->totalPoints }}</strong></td>
                                        <td><strong>{{ $student->points->averagePoints }}</strong></td>
                                        <td><strong>{{ $student->points->averageGrade }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div>
                        <h6>GRADES DISTRIBUTION</h6>
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>ENTRY</th>
                                    @foreach ($section->gradesData['grades'] as $grade)
                                        <th>{{ $grade['grade_name'] }}</th>
                                    @endforeach
                                    <th>X</th>
                                    <th>Y</th>
                                    <th>Z</th>
                                    <th>MN</th>
                                    <th>GD</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $section->gradesData['totalStudents'] }}</td>
                                    @foreach ($section->gradesData['grades'] as $grade)
                                        <td>{{ $grade['totalGrades'] }}</td>
                                    @endforeach
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td><strong>{{ $section->gradesData['averagePoints'] }}</strong>
                                    </td>
                                    <td><strong>{{ $section->gradesData['averageGrade'] }}</strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <h6>SUBJECTS ANALYSIS</h6>
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>RNK</th>
                                    <th style="text-align: left">SUBJECTS</th>
                                    @foreach ($grades as $grade)
                                        <th>{{ $grade['grade_name'] }}</th>
                                    @endforeach
                                    <th>ENT</th>
                                    <th>PTS</th>
                                    <th>MG</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($section->subjects as $subject)
                                    <tr>
                                        <td><strong>{{ $subject->subjectPosition }}</strong></td>
                                        <td style="text-align: left">
                                            <strong>{{ strtoupper($subject->subject_name) }}</strong>
                                        </td>
                                        @foreach ($subject->grades as $grade)
                                            <td>{{ $grade['totalGrades'] }}</td>
                                        @endforeach
                                        <td><strong>{{ $subject->subjectEntry }}</strong></td>
                                        <td><strong>{{ $subject->subjectPoints }}</strong></td>
                                        <td><strong>{{ $subject->subjectGrade }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </main>
    @endforeach
    <main>
        @if (empty($classData['students']))
            <p>Class students list empty</p>
        @else
            <h6>OVERAL PERFORMANCE / {{ strtoupper($form->form_name) }} /
                {{ $exam->name }} /
                {{ $exam->year }}</h6>
            <table class="table table-sm table-bordered">
                <thead>
                    <tr style="background-color: grey">
                        <th>C.P</th>
                        <th>ADM. NO</th>
                        <th style="text-align: left">NAMES</th>
                        <th>CLASS</th>
                        @foreach ($subjects as $subject)
                            <th>{{ $subject->subject_short }}</th>
                        @endforeach
                        <th>KCPE</th>
                        <th>ENT</th>
                        <th>TPS</th>
                        <th>AVP</th>
                        <th>AGG</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($classData['students'] as $student)
                        <tr>
                            <td><strong>{{ $student->classPosition }}</strong></td>
                            <td><strong>{{ $student->admission_no }}</strong></td>
                            <td style="text-align: left">{{ $student->name }}</td>
                            <td>{{ $student->section_numeric . $student->section_name }} </td>
                            @foreach ($student->subjectScores as $score)
                                <td style="text-align: center">
                                    {{ $score->subjectScore . $score->subjectGrade }}</td>
                            @endforeach
                            <td>{{ $student->kcpe_marks }}</td>
                            <td><strong>{{ $student->subjectsEntry }}</strong></td>
                            <td><strong>{{ $student->points->totalPoints }}</strong></td>
                            <td><strong>{{ $student->points->averagePoints }}</strong></td>
                            <td><strong>{{ $student->points->averageGrade }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <h6>GRADES DISTRIBUTION</h6>
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>ENTRY</th>
                        @foreach ($classData['gradesData']['grades'] as $grade)
                            <th>{{ $grade['grade_name'] }}</th>
                        @endforeach
                        <th>X</th>
                        <th>Y</th>
                        <th>Z</th>
                        <th>MN</th>
                        <th>GD</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $classData['gradesData']['totalStudents'] }}</td>
                        @foreach ($classData['gradesData']['grades'] as $grade)
                            <td>{{ $grade['totalGrades'] }}</td>
                        @endforeach
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td><strong>{{ $classData['gradesData']['averagePoints'] }}</strong>
                        </td>
                        <td><strong>{{ $classData['gradesData']['averageGrade'] }}</strong>
                        </td>
                    </tr>
                </tbody>
            </table>
            <h6>SUBJECTS ANALYSIS</h6>
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>RNK</th>
                        <th style="text-align: left">SUBJECTS</th>
                        @foreach ($grades as $grade)
                            <th>{{ $grade['grade_name'] }}</th>
                        @endforeach
                        <th>ENT</th>
                        <th>PTS</th>
                        <th>MG</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($classData['subjects'] as $subject)
                        <tr>
                            <td><strong>{{ $subject->subjectPosition }}</strong></td>
                            <td style="text-align: left"><strong>{{ strtoupper($subject->subject_name) }}</strong>
                            </td>
                            @foreach ($subject->grades as $grade)
                                <td>{{ $grade['totalGrades'] }}</td>
                            @endforeach
                            <td><strong>{{ $subject->subjectEntry }}</strong></td>
                            <td><strong>{{ $subject->subjectPoints }}</strong></td>
                            <td><strong>{{ $subject->subjectGrade }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </main>
@endsection
