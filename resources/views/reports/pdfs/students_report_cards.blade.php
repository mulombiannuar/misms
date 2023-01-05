@extends('layouts.app.report')

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
                            <th style="border-style: none;">{{ strtoupper($exam->name) }} TERM
                                <?php echo ' ' . $exam->year; ?></th>
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
                    <tfoot>
                        <tr>
                            <th>&nbsp;</th>
                            <th style="text-align: left">SUBJECTS ENTRY : {{ $student->examDetails->subjects_entry }}</th>
                            <th style="text-align: center">
                                {{ $student->examDetails->average_points . $student->examDetails->average_grade }}</th>
                            <th style="text-align: center">
                                {{ $student->examDetails->section_position . '/' . $student->classEntries['sectionEntry'] }}
                            </th>
                            <th style="text-align: center">
                                {{ $student->examDetails->class_position . '/' . $student->classEntries['classEntry'] }}
                            </th>
                            <th style="text-align: left">{{ $student->classTeacher['teacher_remarks'] }}</th>
                            <th style="text-align: center">{{ $student->classTeacher['teacher'] }}</th>
                        </tr>
                    </tfoot>
                </table>

                <!--./student term performance table -->
                {{-- <table class="outer-table">
                    <thead>
                        <tr>
                            <th>TERM EXAMS SUMMARY</th>
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
                                            <th>TTM</th>
                                            <th>MG</th>
                                            <th>SP</th>
                                            <th>CP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($student->examsDetails as $exam)
                                            <tr>
                                                <td>{{ $exam->name }}</td>
                                                <td>{{ $exam->marks }}</td>
                                                <td>{{ $exam->average_grade }}</td>
                                                <td>{{ $exam->position . '/' . $exam->class_entry }}</td>
                                                <td>{{ $exam->class_position . '/' . $student->classEntry }}</td>
                                            </tr>
                                        @endforeach
                                        @if ($student->examsDetails->count() < 2)
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
                                            <td><strong>MARKS</strong></td>
                                            <td>{{ $student->points->totalMarks }}</td>
                                            <td><strong>MEAN</strong></td>
                                            <td>{{ $student->points->averageMarks }}</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>STREAM POS :</strong></td>
                                            <td>{{ $student->sectionPosition->position }}</td>
                                            <td><strong>Out Of</strong></td>
                                            <td>{{ $student->sectionEntry }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>OVERAL POS :</strong></td>
                                            <td>{{ $student->classPosition }}</td>
                                            <td><strong>Out Of</strong></td>
                                            <td>{{ $student->classEntry }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td style="margin-top: 0px;">
                                <h3><sup>{{ $student->classPosition }}</sup>/<sub>{{ $student->classEntry }}</sub></h3>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!--./ student performance graph-->
                <div class="graph">
                    <img src="data:image/png;base64,<?php echo base64_encode($student->graphData); ?>" />
                </div>

                <!--./ performance comments-->
                <table style="margin: 10px;">
                    <tbody>
                        <tr>
                            <td style="border-style: none; text-align: left; padding-left: 10px;"><strong>CLASS
                                    TEACHER</strong></td>
                            <td style="border-style: none; text-align: left;"><span
                                    style="font: italic 13px;">{{ $student->comments->score_remarks }}</span>
                            </td>
                            <td style="border-style: none;">
                                <strong>{{ strtoupper($student->classTeacher->name) }}</strong>
                            </td>
                        </tr>

                        <tr>
                            <td style="border-style: none; text-align:  left; padding-left: 10px;"><strong>HEAD
                                    TEACHER</strong></td>
                            <td style="border-style: none; text-align: left;"><span
                                    style="font: italic 13px;">{{ $student->comments->principal_remarks }}</span>
                            </td>
                            <td style="border-style: none;">
                                <strong>ANNUAR MULOMBI</strong>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!--./ Fees details issue -->
                <table style="margin-bottom: 10px;">
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
                            <th style="border: solid #ccc;" colspan="3">CLOSING DATE : {{ $dates->closingDate }}</th>
                            <th style="border: solid #ccc;" colspan="3">OPENING DATE : {{ $dates->openingDate }}</th>
                        </tr>
                    </thead>
                </table>

                <footer>
                    <img src="{{ asset('storage/images/system/miems-report-footer.jpg') }}">
                </footer>
                <div class="footer-text">
                    <small>
                        Printed on <?php echo date('F d, Y h:i:sa'); ?>. &nbsp;&nbsp;&nbsp;&nbsp;
                        Serial No. <?php echo 'MIEMS/RC/' . $student->admission_no . '/' . $exam->term . '/' . $year; ?>
                        &nbsp;&nbsp;&nbsp;&nbsp; Designed by Mulan Technologies &nbsp;&nbsp;&nbsp; TEL:
                        0703539208 &nbsp;&nbsp; EMAIL : info@mulan.co.ke
                    </small>
                </div> --}}
            </div>
        </div>
    @endforeach
@endsection
