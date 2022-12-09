@extends('layouts.app.table')

@section('content')
    <!-- Main content -->
    <x-section>
        <x-card class="card-warning" icon="fa-list-alt" :title="$title">
            <div class="text-right">
                <a href="{{ route('attendances.class-attendances.create') }}">
                    <x-buttons.button class="margin mb-2  btn-secondary" buttonName="Add New Attendance"
                        buttonIcon="fa-plus-circle" />
                </a>

                <x-buttons.button class="margin mb-2 btn-info" buttonName="View Attendance Report" buttonIcon="fa-calendar"
                    data-toggle="modal" data-target="#modalReport" />
            </div>
            <x-table.table id="table1">
                <x-table.thead>
                    <th>S.N</th>
                    <th>ATTENDANCES DATES</th>
                    <th>SESSION</th>
                    <th>CLASS</th>
                    <th>ACTION BY</th>
                    <th>ACTIONS</th>
                </x-table.thead>
                <tbody>
                    @foreach ($attendances as $attendance)
                        <tr>
                            <td>{{ $attendance->attendance_id }}</td>
                            <td><strong>{{ date('D, d M Y', strtotime($attendance->date)) }}</strong></td>
                            <td>{{ 'Term ' . $attendance->term . ' - ' . $attendance->session }}</td>
                            <td>{{ $attendance->section_numeric . $attendance->section_name }}</td>
                            <td>{{ $attendance->teacher_name }}</td>
                            <td>
                                <a href="{{ route('attendances.class-attendances.show', $attendance->attendance_id) }}">
                                    <x-buttons.button class="btn btn-sm btn-info" buttonName="View Students"
                                        buttonIcon="fa-bars" />
                                </a>
                                <x-buttons.delete
                                    action="{{ route('attendances.class-attendances.destroy', $attendance->attendance_id) }}"
                                    btnSize="btn-sm" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-table.table>

            <x-form.modal id="modalReport" modalSize="modal-md" modalTitle="Students Attendance Report">
                <form role="form" action="{{ route('reports.pdfs.students') }}">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="start_date">Start Date</label>
                                <input type="date" name="start_date" class="form-control" id="start_date"
                                    value="{{ date_format(date_create(now()), 'Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="end_date">End Date</label>
                                <input type="date" name="end_date" class="form-control" id="end_date"
                                    value="{{ date_format(date_create(now()), 'Y-m-d') }}" required>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-info"> <i class="fa fa-calendar"></i>
                            View Attendance Report</button>
                    </div>
                </form>
            </x-form.modal>

        </x-card>
        <!-- /.card component -->
    </x-section>
    <!-- /.section component -->
@endsection
