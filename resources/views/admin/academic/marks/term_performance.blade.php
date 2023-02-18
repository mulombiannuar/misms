@extends('layouts.app.table')

@section('content')
    <!-- Main content -->
    <x-section>
        <x-card class="card-secondary" icon="fa-edit" :title="$title">
            @if (count($classData['students']) == 0)
                <div class="alert alert-info">
                    No students found for this exam or marks analysis has not taken place
                </div>
            @else
                <table id="table1"
                    class="table table-sm table-hover table-responsive table-bordered table-head-fixed text-nowrap">
                    <thead>
                        <tr>
                            <th>CP</th>
                            <th>SP</th>
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
                            <th>DEV</th>
                            <th>AGG</th>
                            <th>REPORTS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($classData['students'] as $student)
                            <tr>
                                <td><strong>{{ $student->examDetails->class_position }}</strong>
                                </td>
                                <td><strong>{{ $student->examDetails->section_position }}</strong>
                                </td>
                                <td><strong>{{ $student->admission_no }}</strong></td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->section_numeric . $student->section_name }} </td>
                                @foreach ($student->subjectScores as $score)
                                    <td style="text-align: center">
                                        {{ $score->subjectScore . $score->subjectGrade }}</td>
                                @endforeach
                                <td>{{ $student->kcpe_marks }}</td>
                                <td><strong>{{ $student->examDetails->subjects_entry }}</strong>
                                </td>
                                <td><strong>{{ $student->examDetails->total_points }}</strong>
                                </td>
                                <td><strong>{{ $student->examDetails->average_points }}</strong>
                                </td>
                                <td><strong>{{ $student->studentDev > 0 ? '+' . number_format($student->studentDev, 2) : number_format($student->studentDev, 2) }}</strong>
                                </td>
                                <td><strong>{{ $student->examDetails->average_grade }}</strong>
                                </td>
                                <td>
                                    <a target="_new"
                                        href="{{ route('marks.reports.studentmeanreport', ['student_id' => $student->student_id, 'year' => $student->examDetails->year, 'term' => $student->examDetails->term, 'class_numeric' => $student->section_numeric]) }}">
                                        <x-buttons.button class="btn btn-xs btn-secondary" buttonName="View Report"
                                            buttonIcon="fa-bars" />
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </x-card>
        <!-- /.card component -->
    </x-section>
    <!-- /.section component -->
@endsection
