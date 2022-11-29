@extends('layouts.app.table')

@section('content')
    <!-- Main content -->
    <x-section class="content">
        <div class="card card-primary card-outline">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#rooms" data-toggle="tab"><i
                                class="fa fa-list-alt"></i>
                            Hostel Details</a></li>
                    <li class="nav-item"><a class="nav-link" href="#students" data-toggle="tab"><i class="fa fa-users"></i>
                            Hostel Students</a></li>
                </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="rooms">
                        <!-- Add Room Label -->
                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fa fa-plus-circle"></i> Add Room Label</h3>
                            </div>
                            <div class="card-body">
                                <x-form.form action="{{ route('hostel.rooms.store') }}" method="post"
                                    buttonName="Add New Room" buttonIcon="fa-plus" buttonClass="btn-info">
                                    <input type="hidden" name="hostel_id" value="{{ $hostel->hostel_id }}">

                                    <x-form.input class="col-md-6 col-sm-12" label="Room label" type="text"
                                        name="room_label" placeholder="Room label e.g J001" value="" />

                                    <x-form.input class="col-md-6 col-sm-12" label="Room Capacity " type="number"
                                        name="room_capacity" placeholder="Room Capacity e.g 4" value="" />

                                </x-form.form>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.Add Room Label -->
                        <!-- Room Labels -->
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fa fa-list"></i> Room Labels</h3>
                            </div>
                            <div class="card-body">
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
                                                    <x-buttons.button class="btn-primary btn-xs" buttonName="Edit"
                                                        buttonIcon="fa-edit" data-toggle="modal"
                                                        data-target="#modal-{{ $room->room_id }}" />

                                                    <a href="{{ route('hostel.rooms.show', $room->room_id) }}">
                                                        <x-buttons.button class="btn-info btn-xs" buttonName="Show"
                                                            buttonIcon="fa-bars" />
                                                    </a>
                                                    <x-buttons.delete
                                                        action="{{ route('hostel.rooms.destroy', $room->room_id) }}"
                                                        btnSize="btn-xs" />
                                                </td>
                                            </tr>
                                            <x-form.modal id="modal-{{ $room->room_id }}" modalSize="modal-md"
                                                modalTitle="Update Room {{ $room->room_label }}">
                                                <x-form.form action="{{ route('hostel.rooms.update', $room->room_id) }}"
                                                    method="put" buttonName="Update Room" buttonIcon="fa-edit"
                                                    buttonClass="btn-info">

                                                    <x-form.input class="col-md-6 col-sm-12" label="Room label"
                                                        type="text" name="room_label" placeholder="Room label e.g J001"
                                                        value="{{ $room->room_label }}" />

                                                    <x-form.input class="col-md-6 col-sm-12" label="Room Capacity "
                                                        type="number" name="room_capacity"
                                                        placeholder="Room Capacity e.g 4"
                                                        value="{{ $room->room_capacity }}" />

                                                </x-form.form>
                                            </x-form.modal>
                                        @endforeach
                                    </tbody>
                                </x-table.table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.Room Labels -->
                    </div>
                    <!-- /.tab-pane -->

                    <div class="tab-pane" id="students">
                        <!-- documents -->
                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fa fa-users"></i> Students</h3>
                            </div>
                            <div class="card-body">
                                <x-table.table id="table2">
                                    <x-table.thead>
                                        <th>S.N</th>
                                        <th>ADM NO.</th>
                                        <th>STUDENT NAME</th>
                                        <th>BED SPACE LABEL</th>
                                        <th>Action</th>
                                    </x-table.thead>
                                    <tbody>

                                    </tbody>
                                </x-table.table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- documents -->
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div><!-- /.card-body -->
        </div>
        <!-- /.nav-tabs-custom -->
    </x-section>
    <!-- /.section component -->
@endsection
