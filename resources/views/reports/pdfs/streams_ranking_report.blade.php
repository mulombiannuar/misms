@extends('layouts.app.report')

@section('content')
    <main>
        <h6>CLASS PERFORMANCE / {{ strtoupper($form->form_name) }} /
            {{ $exam->name }} /
            {{ $exam->year }}</h6>
        <h6>Grades Distribution</h6>
        <table class="table table-sm table-bordered">
            <thead>
                <tr style="background-color: gray">
                    <th>STREAM</th>
                    <th>ENTRY</th>
                    @foreach ($grades as $grade)
                        <th>{{ $grade['grade_name'] }}</th>
                    @endforeach
                    <th>MN</th>
                    <th>DEV</th>
                    <th>GD</th>
                    <th>PST</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($streams as $section)
                    <tr>
                        <td>{{ $section->section_numeric . $section->section_name }}</td>
                        <td>{{ $section->total_students }}</td>
                        @foreach (json_decode($section->grades) as $grade)
                            <td>{{ $grade->totalGrades }}</td>
                        @endforeach
                        <td><strong>{{ $section->average_points }}</strong>
                        </td>
                        <td><strong>{{ $section->examDev > 0 ? '+' . number_format($section->examDev, 2) : $section->examDev }}</strong>
                        </td>
                        <td><strong>{{ $section->average_grade }}</strong>
                        </td>
                        <td><strong>{{ $section->position }}</strong>
                        </td>
                    </tr>
                @endforeach
                <tr style="background-color: rgb(204, 201, 201)">
                    <td><strong>TOTAL</strong></td>
                    <td><strong>{{ $classData['gradesData']->total_students }}</strong></td>
                    @foreach (json_decode($classData['gradesData']->grades) as $grade)
                        <td><strong>{{ $grade->totalGrades }}</strong></td>
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
                    <td>#</td>
                </tr>
            </tbody>
        </table>
    </main>
@endsection
