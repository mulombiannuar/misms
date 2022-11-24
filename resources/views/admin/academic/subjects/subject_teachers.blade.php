@extends('layouts.app.table')

@section('content')
    <!-- Main content -->
    <x-section>
        <x-card class="card-primary" icon="fa-envelope" :title="$title">
            <x-table.table id="table1">
                <x-table.thead>
                    <th>S.N</th>
                    <th>TEACHERS</th>
                    <th>SUBJECTS</th>
                    <th>CLASS</th>
                    <th>DATE CREATED</th>
                    <th>ACTION</th>
                </x-table.thead>
                <tbody>
                    @foreach ($subjects as $subject)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ ucwords($subject->name) }}</td>
                            <td>{{ $subject->subject_name }}</td>
                            <td>{{ $subject->section_numeric . $subject->section_name }}
                            </td>
                            <td>{{ $subject->created_at }}</td>
                            <td>
                                <x-buttons.delete action="{{ route('admin.subject-teachers.destroy', $subject->sub_id) }}"
                                    btnSize="btn-xs" />
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
