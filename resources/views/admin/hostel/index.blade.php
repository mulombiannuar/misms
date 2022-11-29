@extends('layouts.app.table')

@section('content')
    <!-- Main content -->
    <x-section>
        <x-card class="card-primary" icon="fa-list" :title="$title">
            <div class="text-right">
                <a href="{{ route('hostel.hostels.create') }}">
                    <x-buttons.button class="margin mb-2  btn-secondary" buttonName="Add New Hostel" buttonIcon="fa-plus" />
                </a>
            </div>
            <x-table.table id="table1">
                <x-table.thead>
                    <th>C.N</th>
                    <th>NAMES</th>
                    <th>H.MASTER</th>
                    <th>CAPACITY</th>
                    <th>CREATED AT</th>
                    <th>ACTIONS</th>
                </x-table.thead>
                <tbody>
                    @foreach ($hostels as $hostel)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ ucwords($hostel->hostel_name) }}</td>
                            <td>{{ ucwords($hostel->name) }}</td>
                            <td>{{ $hostel->hostel_capacity }}</td>
                            <td>{{ $hostel->created_at }}</td>
                            <td>
                                <a href="{{ route('hostel.hostels.edit', $hostel->hostel_id) }}">
                                    <x-buttons.button class="btn btn-xs btn-secondary" buttonName="Edit"
                                        buttonIcon="fa-edit" />
                                </a>
                                <a href="{{ route('hostel.hostels.show', $hostel->hostel_id) }}">
                                    <x-buttons.button class="btn btn-xs btn-info" buttonName="Show" buttonIcon="fa-bars" />
                                </a>
                                <x-buttons.delete action="{{ route('hostel.hostels.destroy', $hostel->hostel_id) }}"
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
