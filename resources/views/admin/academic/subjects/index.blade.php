@extends('layouts.app.table')

@section('content')
    <!-- Main content -->
    <x-section>
        <x-card class="card-primary" icon="fa-list" :title="$title">
            <div class="text-right">
                <a href="{{ route('admin.subjects.create') }}">
                    <x-buttons.button class="margin mb-2  btn-secondary" buttonName="Add New Subject" buttonIcon="fa-plus" />
                </a>
            </div>
            <x-table.table id="table1">
                <x-table.thead>
                    <th>S.N</th>
                    <th>CODE</th>
                    <th>NAMES</th>
                    <th>SHORT</th>
                    <th>OPTION</th>
                    <th>GROUP</th>
                    <th>CREATED AT</th>
                    <th>ACTIONS</th>
                </x-table.thead>
                <tbody>
                    @foreach ($subjects as $subject)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $subject->subject_code }}</td>
                            <td><strong>{{ $subject->subject_name }}</strong></td>
                            <td>{{ $subject->subject_short }}</td>
                            <td>{{ $subject->optionality }}</td>
                            <td><strong>{{ strtoupper($subject->group) }}</strong></td>
                            <td>{{ $subject->created_at }}</td>
                            <td>
                                <a href="{{ route('admin.subjects.edit', $subject->subject_id) }}">
                                    <x-buttons.button class="btn btn-xs btn-info" buttonName="Edit" buttonIcon="fa-edit" />
                                </a>
                                <x-buttons.delete action="{{ route('admin.subjects.destroy', $subject->subject_id) }}"
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
