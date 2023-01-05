@extends('layouts.app.report')

@section('content')
    @foreach ($sections as $section)
        <main style="page-break-after: always;">
            <h6>SECTION PERFORMANCE / {{ strtoupper($section->section_numeric . $section->section_name) }} /
                {{ $exam->name }} /
                {{ $exam->year }}</h6>
            <h6>Grades Distribution</h6>
            <table class="table table-sm table-bordered">
                <thead>
                    <tr style="background-color: gray">
                        <th>ENTRY</th>
                        @foreach (json_decode($section->gradesData->grades) as $grade)
                            <th>{{ $grade->grade_name }}</th>
                        @endforeach
                        {{-- <th>X</th>
                        <th>Y</th>
                        <th>Z</th> --}}
                        <th>MN</th>
                        <th>DEV</th>
                        <th>GD</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $section->gradesData->total_students }}</td>
                        @foreach (json_decode($section->gradesData->grades) as $grade)
                            <td>{{ $grade->totalGrades }}</td>
                        @endforeach
                        {{-- <td>0</td>
                        <td>0</td>
                        <td>0</td> --}}
                        <td><strong>{{ $section->gradesData->average_points }}</strong>
                        </td>
                        <td><strong>{{ $section->examDev > 0 ? '+' . number_format($section->examDev, 2) : $section->examDev }}</strong>
                        </td>
                        <td><strong>{{ $section->gradesData->average_grade }}</strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </main>
    @endforeach
    <main>
        <h6>CLASS PERFORMANCE / {{ strtoupper($form->form_name) }} /
            {{ $exam->name }} /
            {{ $exam->year }}</h6>
        <div>
            <h6>Grades Distribution</h6>
            <table class="table table-sm table-bordered">
                <thead>
                    <tr style="background-color: gray">
                        <th>ENTRY</th>
                        @foreach (json_decode($classData['gradesData']->grades) as $grade)
                            <th>{{ $grade->grade_name }}</th>
                        @endforeach
                        {{-- <th>X</th>
                        <th>Y</th>
                        <th>Z</th> --}}
                        <th>MN</th>
                        <th>DEV</th>
                        <th>GD</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $classData['gradesData']->total_students }}</td>
                        @foreach (json_decode($classData['gradesData']->grades) as $grade)
                            <td>{{ $grade->totalGrades }}</td>
                        @endforeach
                        {{-- <td>0</td>
                        <td>0</td>
                        <td>0</td> --}}
                        <td><strong>{{ $classData['gradesData']->average_points }}</strong>
                        </td>
                        <td><strong>{{ $classData['examDev'] > 0 ? '+' . number_format($classData['examDev'], 2) : $classData['examDev'] }}</strong>
                        </td>
                        <td><strong>{{ $classData['gradesData']->average_grade }}</strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
@endsection
