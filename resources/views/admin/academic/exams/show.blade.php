@extends('layouts.app.table')

@section('content')
    <!-- Main content -->
    <x-section class="content">
        <div class="card card-secondary card-outline">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#details" data-toggle="tab"><i
                                class="fa fa-graduation-cap"></i>
                            Exam Details</a></li>
                    <li class="nav-item"><a class="nav-link" href="#students" data-toggle="tab"><i class="fa fa-users"></i>
                            Students</a></li>
                    <li class="nav-item"><a class="nav-link" href="#reports" data-toggle="tab"><i class="fa fa-list"></i>
                            Report Cards</a></li>
                </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="details">
                        <!-- Profile -->
                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fa fa-graduation-cap"></i> Exam Details</h3>
                            </div>
                            <div class="card-body">
                                <x-form.form action="{{ route('admin.exams.update', $exam->exam_id) }}" method="put"
                                    buttonName="Update Exam Data" buttonIcon="fa-edit" buttonClass="btn-secondary">

                                    <x-form.input class="col-md-3 col-sm-12" label="Exam name" type="text" name="name"
                                        placeholder="Exam name e.g C.A.T 1 Midterm 2022" value="{{ $exam->name }}" />

                                    <x-form.input class="col-md-3 col-sm-12" label="Start Date" type="date"
                                        name="start_date" placeholder="Select date start date"
                                        value="{{ date_format(date_create($exam->start_date), 'Y-m-d') }}" />

                                    <x-form.input class="col-md-3 col-sm-6" label="End Date" type="date" name="end_date"
                                        placeholder="Selext end date"
                                        value="{{ date_format(date_create($exam->end_date), 'Y-m-d') }}" />

                                    <x-form.input class="col-md-3 col-sm-12" label="Deadline Date" type="date"
                                        name="deadline_date" placeholder="Select deadline date"
                                        value="{{ date_format(date_create($exam->deadline_date), 'Y-m-d') }}" />

                                    <x-form.select class="col-md-3 col-sm-12" value="{{ $exam->class_numeric }}"
                                        label="Class" name="class_numeric">
                                        @foreach ($forms as $form)
                                            <option value="{{ $form->form_numeric }}">
                                                {{ $form->form_name }}</option>
                                        @endforeach
                                    </x-form.select>

                                    <x-form.select class="col-md-3 col-sm-12" value="{{ $exam->term }}" label="Term"
                                        name="term">
                                        <option value="1">Term 1</option>
                                        <option value="2">Term 2</option>
                                        <option value="3">Term 3</option>
                                    </x-form.select>

                                    <x-form.select class="col-md-3 col-sm-12" value="{{ $exam->converted }}"
                                        label="Exam Converted?" name="converted">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </x-form.select>

                                    <x-form.select class="col-md-3 col-sm-12" value="{{ $exam->conversion }}"
                                        label="Exam Conversion" name="conversion">
                                        <option value="">Select Conversion</option>
                                        <option value="0.15">15%</option>
                                        <option value="0.30">30%</option>
                                        <option value="0.50">50%</option>
                                        <option value="0.70">70%</option>
                                        <option value="1.0">100%</option>
                                    </x-form.select>

                                    <x-form.textarea class="col-sm-12" label="Extra information" name="notes"
                                        placeholder="Enter exam extra information" value="{{ $exam->notes }}" />
                                </x-form.form>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.Profile -->
                    </div>
                    <!-- /.tab-pane -->

                    <div class="tab-pane" id="students">
                        <!-- documents -->
                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fa fa-users"></i> Exam Students ({{ count($students) }})
                                </h3>
                            </div>
                            <div class="card-body">
                                @if (count($students) == 0)
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
                                            @foreach ($students as $student)
                                                <tr>
                                                    <td><strong>{{ $student->examData->examDetails->class_position }}</strong>
                                                    </td>
                                                    <td><strong>{{ $student->examData->examDetails->section_position }}</strong>
                                                    </td>
                                                    <td><strong>{{ $student->admission_no }}</strong></td>
                                                    <td>{{ $student->name }}</td>
                                                    <td>{{ $student->section_numeric . $student->section_name }} </td>
                                                    @foreach ($student->examData->subjectScores as $score)
                                                        <td style="text-align: center">
                                                            {{ $score->subjectScore . $score->subjectGrade }}</td>
                                                    @endforeach
                                                    <td>{{ $student->kcpe_marks }}</td>
                                                    <td><strong>{{ $student->examData->examDetails->subjects_entry }}</strong>
                                                    </td>
                                                    <td><strong>{{ $student->examData->examDetails->total_points }}</strong>
                                                    </td>
                                                    <td><strong>{{ $student->examData->examDetails->average_points }}</strong>
                                                    </td>
                                                    <td><strong>{{ $student->examData->studentDev > 0 ? '+' . number_format($student->examData->studentDev, 2) : number_format($student->examData->studentDev, 2) }}</strong>
                                                    </td>
                                                    <td><strong>{{ $student->examData->examDetails->average_grade }}</strong>
                                                    </td>
                                                    <td>
                                                        <a target="_new"
                                                            href="{{ route('marks.reports.studentreport', ['exam' => $exam->exam_id, 'student_id' => $student->student_id]) }}">
                                                            <x-buttons.button class="btn btn-xs btn-secondary"
                                                                buttonName="View Report" buttonIcon="fa-bars" />
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- documents -->
                    </div>
                    <!-- /.tab-pane -->

                    <div class="tab-pane" id="reports">
                        <!-- documents -->
                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fa fa-list"></i> Report Cards</h3>
                            </div>
                            <div class="card-body">
                                Report Cards
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- documents -->
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
