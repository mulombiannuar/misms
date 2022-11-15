@extends('layouts.app.table')

@section('content')
    <!-- Main content -->
    <x-section>
        <x-card class="card-primary" icon="fa-list" title="{{ $title }} ({{ $logs->count() }})">
            <x-table.table id="table1">
                <x-table.thead>
                    <th>S.N</th>
                    <th>LOGGED DATE</th>
                    <th>USERNAME</th>
                    <th>EMAIL</th>
                    <th>ACTIVITY TYPE</th>
                    <th>DESCRIPTION</th>
                    <th>IP ADDRESS</th>
                </x-table.thead>
                <tbody>
                    @foreach ($logs as $log)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $log->date }}</td>
                            <td>{{ $log->name }}</td>
                            <td>{{ $log->email }}</td>
                            <td>{{ $log->activity_type }}</td>
                            <td>{{ $log->description }}</td>
                            <td>{{ $log->ip_address }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </x-table.table>
        </x-card>
        <!-- /.card component -->
    </x-section>
    <!-- /.section component -->
@endsection
