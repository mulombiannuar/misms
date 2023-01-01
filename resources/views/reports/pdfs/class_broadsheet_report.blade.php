@extends('layouts.app.report')

@section('content')
    @foreach ($sections as $section)
        <main style="page-break-after: always;">
            <h6>SECTION PERFORMANCE / {{ strtoupper($section->section_numeric . $section->section_name) }} /
                {{ $exam->name }} /
                {{ $exam->year }}</h6>
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
                            <th>DEV</th>
                            <th>AGG</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($section->students as $student)
                            <tr>
                                <td><strong>{{ $student->examDetails->section_position }}</strong></td>
                                <td><strong>{{ $student->admission_no }}</strong></td>
                                <td style="text-align: left">{{ $student->name }}</td>
                                <td>{{ $student->section_numeric . $student->section_name }} </td>
                                @foreach ($student->subjectScores as $score)
                                    <td style="text-align: center">
                                        {{ $score->subjectScore . $score->subjectGrade }}</td>
                                @endforeach
                                <td>{{ $student->kcpe_marks }}</td>
                                <td><strong>{{ $student->examDetails->subjects_entry }}</strong></td>
                                <td><strong>{{ $student->examDetails->total_points }}</strong></td>
                                <td><strong>{{ $student->examDetails->average_points }}</strong></td>
                                <td><strong>{{ $student->studentDev > 0 ? '+' . $student->studentDev : $student->studentDev }}</strong>
                                </td>
                                <td><strong>{{ $student->examDetails->average_grade }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </main>
    @endforeach
    <main>
        <h6>CLASS PERFORMANCE / {{ strtoupper($form->form_name) }} /
            {{ $exam->name }} /
            {{ $exam->year }}</h6>
        <div>
            <table class="table table-sm table-bordered">
                <thead>
                    <tr style="background-color: grey">
                        <th>C.P</th>
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
                        <th>DEV</th>
                        <th>AGG</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($classData['students'] as $student)
                        <tr>
                            <td><strong>{{ $student->examDetails->class_position }}</strong></td>
                            <td><strong>{{ $student->examDetails->section_position }}</strong></td>
                            <td><strong>{{ $student->admission_no }}</strong></td>
                            <td style="text-align: left">{{ $student->name }}</td>
                            <td>{{ $student->section_numeric . $student->section_name }} </td>
                            @foreach ($student->subjectScores as $score)
                                <td style="text-align: center">
                                    {{ $score->subjectScore . $score->subjectGrade }}</td>
                            @endforeach
                            <td>{{ $student->kcpe_marks }}</td>
                            <td><strong>{{ $student->examDetails->subjects_entry }}</strong></td>
                            <td><strong>{{ $student->examDetails->total_points }}</strong></td>
                            <td><strong>{{ $student->examDetails->average_points }}</strong></td>
                            <td><strong>{{ $student->studentDev > 0 ? '+' . $student->studentDev : $student->studentDev }}</strong>
                            </td>
                            <td><strong>{{ $student->examDetails->average_grade }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
@endsection
