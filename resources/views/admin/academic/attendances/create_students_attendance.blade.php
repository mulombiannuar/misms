@extends('layouts.app.table')

@section('content')
    <!-- Main content -->
    <x-section>
        <x-card class="card-primary" icon="fa-list" :title="$title">
            @if (count($students) != 0)
                <x-form.form action="{{ route('attendances.class-attendances.store') }}" method="post"
                    buttonName="Submit Students Data" buttonIcon="fa-plus-circle" buttonClass="btn-secondary">
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
                            @foreach ($students as $student)
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
                                                <select name="attendance_status[]" id="token{{ $loop->iteration }}"
                                                    class="form-control" required>
                                                    <option value="">- Select Status -</option>
                                                    <option value="1">Student present</option>
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
            @else
                <div class="alert alert-warning">
                    This class does not have any students
                </div>
            @endif
        </x-card>
        <!-- /.card component -->
    </x-section>
    <!-- /.section component -->
@endsection
