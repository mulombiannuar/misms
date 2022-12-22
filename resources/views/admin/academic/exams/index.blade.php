@extends('layouts.app.table')

@section('content')
    <!-- Main content -->
    <x-section>
        <x-card class="card-primary" icon="fa-list" :title="$title">
            <div class="text-right">
                <a href="{{ route('admin.exams.create') }}">
                    <x-buttons.button class="margin mb-2  btn-secondary" buttonName="Add New Exam" buttonIcon="fa-plus" />
                </a>
            </div>
            <x-table.table id="table1">
                <x-table.thead>
                    <th>S.N</th>
                    <th>NAMES</th>
                    <th>CLASS</th>
                    <th>TERM</th>
                    <th>START DATE</th>
                    <th>DEADLINE</th>
                    <th>CONVERSION</th>
                    <th>ACTIONS</th>
                </x-table.thead>
                <tbody>
                    @foreach ($exams as $exam)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $exam->name }}</strong></td>
                            <td>{{ $exam->form_name }}</td>
                            <td>TERM {{ $exam->term }}</td>
                            <td>{{ $exam->start_date }}</td>
                            <td><strong>{{ $exam->deadline_date }}</strong></td>
                            <td>{{ $exam->conversion * 100 }}%</td>
                            <td>
                                <a href="{{ route('admin.exams.edit', $exam->exam_id) }}">
                                    <x-buttons.button class="btn btn-xs btn-secondary" buttonName="Edit"
                                        buttonIcon="fa-edit" />
                                </a>
                                <a href="{{ route('admin.exams.show', $exam->exam_id) }}">
                                    <x-buttons.button class="btn btn-xs btn-info" buttonName="Show" buttonIcon="fa-bars" />
                                </a>
                                <x-buttons.delete action="{{ route('admin.exams.destroy', $exam->exam_id) }}"
                                    btnSize="btn-xs" />
                                @if ($exam->status == 0)
                                    <x-buttons.activate action="{{ route('admin.exams.activate', $exam->exam_id) }}"
                                        btnSize="btn-xs" />
                                @else
                                    <x-buttons.deactivate action="{{ route('admin.exams.deactivate', $exam->exam_id) }}"
                                        btnSize="btn-xs" />
                                @endif
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
