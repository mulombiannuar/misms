@extends('layouts.app.table')

@section('content')
    <!-- Main content -->
    <x-section>
        <x-card class="card-warning" icon="fa-list-alt" :title="$title">
            @if (count($students) !== 0)
                <x-table.table id="table1">
                    <x-table.thead>
                        <th>S.N</th>
                        <th>ADM. NO</th>
                        <th>NAMES</th>
                        <th>CLASS</th>
                        <th>STATUS</th>
                        <th>COMMENT</th>
                        <th>ACTION</th>
                    </x-table.thead>
                    <tbody>
                        @foreach ($students as $student)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $student->admission_no }}</strong></td>
                                <td>{{ strtoupper($student->name) }}</td>
                                <td>{{ $section->section_numeric . $section->section_name }}</td>
                                <td>{{ $student->attendance_status == 1 ? 'Present' : 'Absent' }}</td>
                                <td>{{ $student->comment }}</td>
                                <td>
                                    <a
                                        href="{{ route('attendances.class-attendances.student-report', $student->student_id) }}">
                                        <x-buttons.button class="btn btn-xs btn-secondary" buttonName="Report"
                                            buttonIcon="fa-bars" />
                                    </a>
                                    <x-buttons.button class="btn-info btn-xs" buttonName="Update" buttonIcon="fa-edit"
                                        data-toggle="modal" data-target="#modal-{{ $student->student_attendance_id }}" />
                                </td>
                            </tr>
                            <x-form.modal id="modal-{{ $student->student_attendance_id }}" modalSize="modal-lg"
                                modalTitle="Student Attendance {{ strtoupper($student->name) }}">
                                <x-form.form
                                    action="{{ route('attendances.class-attendances.update-attendance', $student->student_attendance_id) }}"
                                    method="put" buttonName="Update Attendance" buttonIcon="fa-user-edit"
                                    buttonClass="btn-info">

                                    <x-form.input class="col-md-6 col-sm-12" label="Teacher comment" type="text"
                                        name="comment" placeholder="Comment" value="{{ $student->comment }}" />

                                    <div class="col-sm-12 col-md-6">
                                        <div class="form-group">
                                            <div class="form-check">
                                                <label for="attendance_status">Attendance Status</label>
                                                <select name="attendance_status"
                                                    id="token{{ $student->student_attendance_id }}" class="form-control"
                                                    required>
                                                    <option value="{{ $student->attendance_status }}" selected>
                                                        {{ $student->attendance_status == 1 ? 'Student present' : 'Student absent' }}
                                                    </option>
                                                    <option value="1">Student present</option>
                                                    <option value="0">Student absent</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </x-form.form>
                            </x-form.modal>
                        @endforeach
                    </tbody>
                </x-table.table>
            @else
                @if (count($sstudents) == 0)
                    <div class="alert alert-warning">
                        This class does not have any students
                    </div>
                @else
                    <x-form.form action="{{ route('attendances.class-attendances.save') }}" method="post"
                        buttonName="Submit Students Data" buttonIcon="fa-plus-circle" buttonClass="btn-secondary">
                        <input type="hidden" name="attendance_id" value="{{ $attendance->attendance_id }}">
                        <x-table.table id="">
                            <x-table.thead>
                                <th>S.N</th>
                                <th>ADM. NO</th>
                                <th>NAMES</th>
                                <th>CLASS</th>
                                <th>STATUS</th>
                                <th>COMMENT</th>
                            </x-table.thead>
                            <tbody>
                                @foreach ($sstudents as $student)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $student->admission_no }}</strong></td>
                                        <td style="display: none"><input type="hidden" name="students[]"
                                                value="{{ $student->student_id }}"> </td>
                                        <td>{{ strtoupper($student->name) }}</td>
                                        <td>{{ $section->section_numeric . $section->section_name }}</td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <select name="status[]" id="token{{ $loop->iteration }}"
                                                        class="form-control" required>
                                                        <option selected value="1">Student present</option>
                                                        <option value="0">Student absent</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" name="comments[]" class="form-control"
                                                placeholder="Enter comment for {{ strtolower($student->name) }}"
                                                autocomplete="on">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </x-table.table>
                    </x-form.form>
                @endif

            @endif
        </x-card>
        <!-- /.card component -->
    </x-section>
    <!-- /.section component -->
@endsection
