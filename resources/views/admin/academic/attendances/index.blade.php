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
        </x-card>
        <!-- /.card component -->
    </x-section>
    <!-- /.section component -->
@endsection
