@extends('layouts.app.table')

@section('content')
    <!-- Main content -->
    <x-section>
        <x-card class="card-primary" icon="fa-envelope" :title="$title">
            <div class="text-right">
                <a href="{{ route('admin.periods.create') }}">
                    <x-buttons.button class="margin mb-2  btn-secondary" buttonName="Add New Period" buttonIcon="fa-plus" />
                </a>
            </div>
            <x-table.table id="table1">
                <x-table.thead>
                    <th>S.N</th>
                    <th>Period</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Date Created</th>
                    <th>Delete</th>
                </x-table.thead>
                <tbody>
                    @foreach ($periods as $period)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $period->period_name }}</td>
                            <td>{{ $period->start_time }}</td>
                            <td>{{ $period->end_time }}</td>
                            <td>{{ $period->created_at }}</td>
                            <td>
                                <a href="{{ route('admin.periods.edit', $period->period_id) }}">
                                    <x-buttons.button class="btn btn-xs btn-info" buttonName="Edit" buttonIcon="fa-edit" />
                                </a>
                                <x-buttons.delete action="{{ route('admin.periods.destroy', $period->period_id) }}"
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
