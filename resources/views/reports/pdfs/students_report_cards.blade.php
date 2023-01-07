@extends('layouts.app.report')
@php
    use App\Models\Academic\Graphs;
@endphp

@section('content')
    @foreach ($classData['students'] as $student)
        <div style="page-break-after: always;">
            <header>
                <div class="details">
                    <table>
                        <tr>
                            <td style="border-style: none;">
                                <div class="photo">
                                    <img src="{{ asset('assets/dist/img/users/' . $student->student_image) }}" width="100%">
                                </div>
                            </td>
                            <td style="border-style: none;">
                                <div class="header-image">
                                    <img src="{{ asset('storage/assets/dist/img/system/miems-exam-header.jpg') }}"
                                        width="100%">
                                </div>
                            </td>
                            <td style="border-style: none;">
                                <div class="photo">
                                    <img src="{{ asset('storage/assets/dist/img/system/logo.png') }}" width="100%">
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </header>
            <div class="details position-top">
                <table>
                    <thead>
                        <tr>
                            <th style="border-style: none;">NAME : {{ strtoupper($student->name) }}</th>
                            <th style="border-style: none;">ADM. NO : {{ strtoupper($student->admission_no) }}</th>
                            <th style="border-style: none;">CLASS : {{ $student->section_numeric . $student->section_name }}
                            </th>
                            <th style="border-style: none;">{{ strtoupper($exam->name . ' ') }} </th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="scores">
                <!--./student score performance table-->
                <table>
                    <thead>
                        <tr>
                            <th>CODE</th>
                            <th style="text-align: left">SUBJECT</th>
                            <th>SCORE</th>
                            <th>S.P</th>
                            <th>C.P</th>
                            <th style="text-align: left">REMARKS</th>
                            <th>INITIALS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($student->subjectScores as $subject)
                            @if ($subject->subjectScore != '--')
                                <tr>
                                    <td style="text-align: center"> {{ $subject->subject_code }}</td>
                                    <td style="text-align: left"> {{ $subject->subject_name }}</td>
                                    <td style="text-align: center"> {{ $subject->subjectScore . $subject->subjectGrade }}
                                    </td>
                                    <td> {{ $subject->subjectSectionPosition }}</td>
                                    <td> {{ $subject->subjectClassPosition }}</td>
                                    <td style="text-align: left"> {{ $subject->subjectRemarks }}</td>
                                    <td style="text-align: center">{{ $subject->subjectTeacher }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>

                <!--./student term performance table -->
                <table class="outer-table">
                    <thead>
                        <tr>
                            <th>PROGRESS EXAMS SUMMARY</th>
                            <th>TERM AVERAGE PERFORMANCE</th>
                            <th>POSITION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <table class="inner-table">
                                    <thead>
                                        <tr>
                                            <th>EXAMS</th>
                                            <th>PTS</th>
                                            <th>MG</th>
                                            <th>SP</th>
                                            <th>CP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($student->otherExams->count() > 0)
                                            @foreach ($student->otherExams as $exam)
                                                @if ($loop->iteration <= 3)
                                                    <tr>
                                                        <td>{{ $exam->name }}</td>
                                                        <td>{{ $exam->average_points }}</td>
                                                        <td>{{ $exam->average_grade }}</td>
                                                        <td>{{ $exam->section_position . '/' . $exam->section_entry }}</td>
                                                        <td>{{ $exam->class_position . '/' . $exam->class_entry }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @else
                                            <tr>
                                                <td>#</td>
                                                <td>#</td>
                                                <td>#</td>
                                                <td>#</td>
                                                <td>#</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </td>
                            <td>
                                <table class="inner-table">
                                    <thead>
                                        <tr>
                                            <td><strong>EXAM DEV</strong></td>
                                            <td>{{ $student->studentDev > 0 ? '+' . number_format($student->studentDev, 2) : number_format($student->studentDev, 2) }}
                                            </td>
                                            <td><strong>MEAN</strong></td>
                                            <td>{{ $student->examDetails->average_points }}</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>STREAM POS :</strong></td>
                                            <td>{{ $student->examDetails->section_position }}</td>
                                            <td><strong>Out Of</strong></td>
                                            <td>{{ $student->classEntries['sectionEntry'] }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>OVERAL POS :</strong></td>
                                            <td>{{ $student->examDetails->class_position }}</td>
                                            <td><strong>Out Of</strong></td>
                                            <td>{{ $student->classEntries['classEntry'] }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td style="margin-top: 0px;">
                                <table class="inner-table">
                                    <tr>
                                        <td>
                                            <h3>{{ $student->examDetails->class_position }}/{{ $student->classEntries['classEntry'] }}
                                            </h3>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h6>M.G
                                                {{ $student->examDetails->total_points . $student->examDetails->average_grade }}
                                            </h6>
                                        </td>
                                    </tr>

                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!--./ student performance graph-->
                <div class="graph" style="margin-bottom: 10px; border:solid 1px; padding: 10px;">
                    <img
                        src="data:image/png;base64,{{ base64_encode(Graphs::getStudentPeformanceGraph($student->student_id)) }}" />
                </div>

                <!--./ performance comments-->
                <table style="margin-bottom: 10px; ">
                    <thead>
                        <tr>
                            <th style="border: solid #ccc;">CLASSTEACHER</th>
                            <th style="border: solid #ccc;  ">
                                {{ strtoupper($student->classTeacher['teacher']->name) }}</th>
                            <th style="border: solid #ccc;">PRINCIPAL TEACHER</th>
                            <th style="border: solid #ccc;">{{ strtoupper($school->principal) }}</th>
                        </tr>
                        <tr>
                            <th style="border: solid #ccc; padding-left:10px; text-align:left; font-style:italic;"
                                colspan="2">
                                {{ $student->classTeacher['teacher_remarks'] }}
                            </th>
                            <th style="border: solid #ccc; padding-left:10px; text-align:left; font-style:italic;"
                                colspan="2">
                                {{ $student->classTeacher['principal_remarks'] }}</th>
                        </tr>
                    </thead>
                </table>

                <!--./ Fees details issue -->
                <table style="margin-bottom: 15px;">
                    <thead>
                        <tr>
                            <th style="border: solid #ccc;">FEES ARREARS</th>
                            <th style="border: solid #ccc; width: 13%">KES. 1200.00</th>
                            <th style="border: solid #ccc;">NEXT TERM FEES</th>
                            <th style="border: solid #ccc;">KES.23000.00</th>
                            <th style="border: solid #ccc;">TOTAL FEES</th>
                            <th style="border: solid #ccc; width: 13%">KES. 24500.00</th>
                        </tr>
                        <tr>
                            <th style="border: solid #ccc;" colspan="3">CLOSING DATE :
                                {{ date_format(date_create($dates->closingDate), 'D, d M Y') }}</th>
                            <th style="border: solid #ccc;" colspan="3">OPENING DATE :
                                {{ date_format(date_create($dates->openingDate), 'D, d M Y') }}</th>
                        </tr>
                    </thead>
                </table>

                <footer>
                    <img src="{{ asset('storage/assets/dist/img/system/miems-report-footer.jpg') }}">
                </footer>

                <div class="footer-text">
                    <small>
                        Printed on {{ date('F d, Y h:i:sa') }}. &nbsp;&nbsp;&nbsp;&nbsp;
                        Serial No. {{ 'MISMS/RC/' . $student->admission_no . '/' . $exam->term . '/' . $exam->year }}
                        &nbsp;&nbsp;&nbsp;&nbsp; Designed by Mulan Technologies &nbsp;&nbsp;&nbsp; TEL:
                        0703539208 &nbsp;&nbsp; EMAIL : info@mulan.co.ke
                    </small>
                </div>
            </div>
        </div>
    @endforeach
@endsection
