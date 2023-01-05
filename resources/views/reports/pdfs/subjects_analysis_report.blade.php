@extends('layouts.app.report')

@section('content')
    @foreach ($sections as $section)
        <main style="page-break-after: always;">
            <h6>SECTION PERFORMANCE / {{ strtoupper($section->section_numeric . $section->section_name) }} /
                {{ $exam->name }} /
                {{ $exam->year }}</h6>
            <h6>Subjects Analysis</h6>
            <div>
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr style="background-color: gray">
                            <th>S.P</th>
                            <th>C.P</th>
                            <th style="text-align: left">SUBJECTS</th>
                            @foreach ($grades as $grade)
                                <th>{{ $grade['grade_name'] }}</th>
                            @endforeach
                            <th>ENT</th>
                            <th>PTS</th>
                            <th>DEV</th>
                            <th>MG</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($section->subjects as $subject)
                            <tr>
                                <td><strong>{{ $subject->sectionPosition }}</strong></td>
                                <td><strong>{{ $subject->classPosition }}</strong></td>
                                <td style="text-align: left">
                                    <strong>{{ strtoupper($subject->subject_name) }}</strong>
                                </td>
                                @foreach (json_decode($subject->grades) as $grade)
                                    <td>{{ $grade->totalGrades }}</td>
                                @endforeach
                                <td><strong>{{ $subject->total_students }}</strong></td>
                                <td><strong>{{ $subject->average_points }}</strong></td>
                                <td><strong>{{ $subject->subjectDev > 0 ? '+' . number_format($subject->subjectDev, 2) : number_format($subject->subjectDev, 2) }}</strong>
                                </td>
                                <td><strong>{{ $subject->average_grade }}</strong></td>
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
            <h6>Subjects Analysis</h6>
            <div>
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr style="background-color: gray">
                            <th>C.P</th>
                            <th style="text-align: left">SUBJECTS</th>
                            @foreach ($grades as $grade)
                                <th>{{ $grade['grade_name'] }}</th>
                            @endforeach
                            <th>ENT</th>
                            <th>PTS</th>
                            <th>DEV</th>
                            <th>MG</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($classData['subjects'] as $subject)
                            <tr>
                                <td><strong>{{ $subject->classPosition }}</strong></td>
                                <td style="text-align: left">
                                    <strong>{{ strtoupper($subject->subject_name) }}</strong>
                                </td>
                                @foreach (json_decode($subject->grades) as $grade)
                                    <td>{{ $grade->totalGrades }}</td>
                                @endforeach
                                <td><strong>{{ $subject->total_students }}</strong></td>
                                <td><strong>{{ $subject->average_points }}</strong></td>
                                <td><strong>{{ $subject->subjectDev > 0 ? '+' . number_format($subject->subjectDev, 2) : number_format($subject->subjectDev, 2) }}</strong>
                                </td>
                                <td><strong>{{ $subject->average_grade }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>
@endsection
