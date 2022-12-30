@extends('layouts.app.table')

@section('content')
    <!-- Main content -->
    <x-section class="content">
        <div class="card card-warning card-outline">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    @foreach ($sections as $section)
                        <li class="nav-item"><a class="nav-link {{ $loop->iteration == 1 ? 'active' : '' }}"
                                href="#section-{{ $section->section_id }}" data-toggle="tab"><i class="fa fa-list"></i>
                                Form {{ strtoupper($section->section_numeric . $section->section_name) }}</a></li>
                    @endforeach
                    <li class="nav-item"><a class="nav-link" href="#overall" data-toggle="tab"><i class="fa fa-users"></i>
                            Class Performance</a></li>
                </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
                <div class="tab-content">
                    @foreach ($sections as $section)
                        <div class="tab-pane {{ $loop->iteration == 1 ? 'active' : '' }}"
                            id="section-{{ $section->section_id }}">
                            @if (empty($section->students))
                                <p>Students empty</p>
                                <!-- /.STUDENTS LIST-->
                            @else
                                <div class="card card-warning">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="fa fa-list"></i>
                                            FORM {{ strtoupper($section->section_numeric . $section->section_name) }}</h3>
                                    </div>
                                    <div class="card-body">

                                        <table
                                            id="table{{ $loop->iteration }}"class="table table-sm table-hover table-bordered table-head-fixed text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th>S.N</th>
                                                    <th>ADM. NO</th>
                                                    <th>NAMES</th>
                                                    <th>CLASS</th>
                                                    @foreach ($subjects as $subject)
                                                        <th>{{ $subject->subject_short }}</th>
                                                    @endforeach
                                                    <th>KCPE</th>
                                                    <th>ENT</th>
                                                    <th>TPS</th>
                                                    <th>AVP</th>
                                                    <th>AGG</th>
                                                    <th>CP</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($section->students as $student)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td><strong>{{ $student->admission_no }}</strong></td>
                                                        <td>{{ $student->name }}</td>
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
                                                        <td><strong>{{ $student->classPosition }}</strong></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>

                                        </table>

                                    </div>
                                    <!-- /.card-body -->
                                </div>

                                <!-- /.GRADES DISTRIBUTION -->
                                <div class="card card-secondary">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="fas fa-bars mr-1"></i> GRADES
                                            DISTRIBUTION
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-striped">
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
                                    </div>
                                </div>

                                <!-- /.SUBJECT ANALYSIS -->
                                <div class="card card-secondary">
                                    <div class="card-header">
                                        <h3 class="card-title"> <i class="fas fa-list mr-1"></i> SUBJECTS
                                            ANALYSIS
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        {{-- {{ print_r($class->subjects) }} --}}
                                        <table class="table table-sm table-striped">
                                            <thead>
                                                <tr>
                                                    <th>RNK</th>
                                                    <th>SUBJECTS</th>
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
                                                        <td><strong>{{ strtoupper($subject->subject_name) }}</strong>
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
                                </div>
                            @endif
                        </div>
                        <!-- /.tab-pane -->
                    @endforeach

                    <div class="tab-pane" id="overall">
                        @if (empty($classData['students']))
                            <p>Class students empty</p>
                        @else
                            <!-- STUDENTS LIST -->
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fa fa-users"></i> Class Performance</h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-hover table-bordered table-head-fixed text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>S.N</th>
                                                <th>ADM. NO</th>
                                                <th>NAMES</th>
                                                <th>CLASS</th>
                                                @foreach ($subjects as $subject)
                                                    <th>{{ $subject->subject_short }}</th>
                                                @endforeach
                                                <th>KCPE</th>
                                                <th>ENT</th>
                                                <th>TPS</th>
                                                <th>AVP</th>
                                                <th>AGG</th>
                                                <th>CP</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($classData['students'] as $student)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td><strong>{{ $student->admission_no }}</strong></td>
                                                    <td>{{ $student->name }}</td>
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
                                                    <td><strong>{{ $student->classPosition }}</strong></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>

                            <!-- /.GRADES DISTRIBUTION -->
                            <div class="card card-secondary">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-bars mr-1"></i> GRADES
                                        DISTRIBUTION
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-striped">
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
                                </div>
                            </div>

                            <!-- /.SUBJECT ANALYSIS -->
                            <div class="card card-secondary">
                                <div class="card-header">
                                    <h3 class="card-title"> <i class="fas fa-list mr-1"></i> SUBJECTS
                                        ANALYSIS
                                    </h3>
                                </div>
                                <div class="card-body">
                                    {{-- {{ print_r($class->subjects) }} --}}
                                    <table class="table table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th>RNK</th>
                                                <th>SUBJECTS</th>
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
                                                    <td><strong>{{ strtoupper($subject->subject_name) }}</strong>
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
                            </div>
                        @endif
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div><!-- /.card-body -->
        </div>
        <!-- /.nav-tabs-custom -->
    </x-section>
    <!-- /.section component -->
@endsection
