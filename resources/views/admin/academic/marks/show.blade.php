@extends('layouts.app.table')

@section('content')
    <!-- Main content -->
    <x-section class="content">
        <div class="card card-warning card-outline">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#students" data-toggle="tab"><i
                                class="fa fa-users"></i>
                            Bulk Students Entry</a></li>
                    <li class="nav-item"><a class="nav-link" href="#student" data-toggle="tab"><i class="fa fa-user"></i>
                            Single Student Entry</a></li>
                    <li class="nav-item"><a class="nav-link" href="#attendance" data-toggle="tab"><i
                                class="fa fa-list-alt"></i> Subject Attendance</a></li>
                </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="students">
                        <!-- Profile -->
                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fa fa-users"></i> Bulk Students Marks Entry</h3>
                            </div>
                            <div class="card-body">
                                @if (count($students_score) !== 0)
                                    <x-table.table id="table2">
                                        <x-table.thead>
                                            <th>S.N</th>
                                            <th>ADM. NO</th>
                                            <th>NAMES</th>
                                            <th>CLASS</th>
                                            <th>SCORES</th>
                                            <th>GRD</th>
                                            <th>ACTIONS</th>
                                        </x-table.thead>
                                        <tbody>
                                            @foreach ($students_score as $student)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td><strong>{{ $student->admission_no }}</strong></td>
                                                    <td>{{ strtoupper($student->name) }}</td>
                                                    <td>{{ $section->section_numeric . $section->section_name }}
                                                    </td>
                                                    <td><strong>{{ $student->score }}</strong></td>
                                                    <td><strong>{{ $student->grade['grade_name'] }}</strong></td>
                                                    <td>
                                                        <a href="{{ route('marks.scores.edit', $student->score_id) }}">
                                                            <x-buttons.button class="btn btn-xs btn-info" buttonName="Edit"
                                                                buttonIcon="fa-edit" />
                                                        </a>
                                                        <x-buttons.delete
                                                            action="{{ route('marks.scores.destroy', $student->score_id) }}"
                                                            btnSize="btn-xs" />
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </x-table.table>
                                @else
                                    @if (count($subject_students) == 0)
                                        <div class="alert alert-warning">
                                            This class does not have such subjec students
                                        </div>
                                    @else
                                        <x-form.form action="{{ route('marks.scores.store') }}" method="post"
                                            buttonName="Submit Students Scores" buttonIcon="fa-plus-circle"
                                            buttonClass="btn-secondary">
                                            <input type="hidden" name="exam_record_id" value="{{ $score->subm_id }}">
                                            <input type="hidden" name="subject_id" value="{{ $score->subject_id }}">
                                            <input type="hidden" name="exam_id" value="{{ $score->exam_id }}">
                                            <input type="hidden" name="section_numeric"
                                                value="{{ $score->section_numeric }}">
                                            <input type="hidden" name="section_id" value="{{ $score->section_id }}">
                                            <x-table.table id="">
                                                <x-table.thead>
                                                    <th>S.N</th>
                                                    <th>ADM. NO</th>
                                                    <th>NAMES</th>
                                                    <th>CLASS</th>
                                                    <th>SUBJECT SCORES</th>
                                                </x-table.thead>
                                                <tbody>
                                                    @foreach ($subject_students as $student)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td><strong>{{ $student->admission_no }}</strong></td>
                                                            <td style="display: none"><input type="hidden"
                                                                    name="students[]" value="{{ $student->student_id }}">
                                                            </td>
                                                            <td>{{ strtoupper($student->name) }}</td>
                                                            <td>{{ $section->section_numeric . $section->section_name }}
                                                            </td>
                                                            <td>
                                                                <input type="number" name="scores[]" class="form-control"
                                                                    placeholder="Enter {{ $score->subject_name }} score for {{ ucwords($student->name) }}"
                                                                    autocomplete="on" required
                                                                    onKeyPress="if(this.value.length==2) return false;"
                                                                    minlength="2" maxlength="2">
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </x-table.table>
                                        </x-form.form>
                                    @endif
                                @endif

                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.Profile -->
                    </div>
                    <!-- /.tab-pane -->

                    <div class="tab-pane" id="student">
                        <!-- documents -->
                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fa fa-user"></i> Single Students Student Entry</h3>
                            </div>
                            <div class="card-body">
                                <x-form.form action="{{ route('marks.scores.save') }}" method="post"
                                    buttonName="Save Student Score" buttonIcon="fa-plus-circle" buttonClass="btn-secondary">
                                    <input type="hidden" name="exam_record_id" value="{{ $score->subm_id }}">
                                    <input type="hidden" name="subject_id" value="{{ $score->subject_id }}">
                                    <input type="hidden" name="exam_id" value="{{ $score->exam_id }}">
                                    <input type="hidden" name="section_numeric" value="{{ $score->section_numeric }}">
                                    <input type="hidden" name="section_id" value="{{ $score->section_id }}">

                                    <x-form.select class="col-sm-6" value="" label="Subject Students" name="student"
                                        id="county">
                                        @foreach ($subject_students as $student)
                                            <option value="{{ $student->student_id }}">
                                                {{ $student->admission_no . ' - ' . $student->name }}</option>
                                        @endforeach
                                    </x-form.select>

                                    <x-form.input class="col-sm-6" label="Student score" type="number" name="score"
                                        placeholder="Student score" value=""
                                        onKeyPress="if(this.value.length==2) return false;" minlength="2"
                                        maxlength="2" />
                                </x-form.form>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- documents -->
                    </div>
                    <!-- /.tab-pane -->

                    <div class="tab-pane" id="attendance">
                        <!-- roles user -->
                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fa fa-list-alt"></i> Subject Attendance</h3>
                            </div>
                            <div class="card-body">
                                Subject Attendance
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- roles user -->
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
