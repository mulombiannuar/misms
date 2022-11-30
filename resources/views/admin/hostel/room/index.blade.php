@extends('layouts.app.table')

@section('content')
    <!-- Main content -->
    <x-section>
        <x-card class="card-primary" icon="fa-list" title="{{ $title }} ({{ count($rooms) }})">
            <x-table.table id="table1">
                <x-table.thead>
                    <th>S.N</th>
                    <th>ROOM LABEL</th>
                    <th>HOSTEL</th>
                    <th>CAPACITY</th>
                    <th>Action</th>
                </x-table.thead>
                <tbody>
                    @foreach ($rooms as $room)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $room->room_label }}</td>
                            <td>{{ $room->hostel_name }}</td>
                            <td>{{ $room->room_capacity }}</td>
                            <td>
                                <x-buttons.button class="btn-primary btn-xs" buttonName="Edit" buttonIcon="fa-edit"
                                    data-toggle="modal" data-target="#modal-{{ $room->room_id }}" />

                                <a href="{{ route('hostel.rooms.show', $room->room_id) }}">
                                    <x-buttons.button class="btn-info btn-xs" buttonName="Show" buttonIcon="fa-bars" />
                                </a>
                                <x-buttons.delete action="{{ route('hostel.rooms.destroy', $room->room_id) }}"
                                    btnSize="btn-xs" />
                            </td>
                        </tr>
                        <x-form.modal id="modal-{{ $room->room_id }}" modalSize="modal-md"
                            modalTitle="Update Room {{ $room->room_label }}">
                            <x-form.form action="{{ route('hostel.rooms.update', $room->room_id) }}" method="put"
                                buttonName="Update Room" buttonIcon="fa-edit" buttonClass="btn-info">

                                <x-form.input class="col-md-6 col-sm-12" label="Room label" type="text" name="room_label"
                                    placeholder="Room label e.g J001" value="{{ $room->room_label }}" />

                                <x-form.input class="col-md-6 col-sm-12" label="Room Capacity " type="number"
                                    name="room_capacity" placeholder="Room Capacity e.g 4"
                                    value="{{ $room->room_capacity }}" />

                            </x-form.form>
                        </x-form.modal>
                    @endforeach
                </tbody>
            </x-table.table>
        </x-card>
        <!-- /.card component -->
    </x-section>
    <!-- /.section component -->
@endsection
