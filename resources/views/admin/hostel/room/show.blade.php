@extends('layouts.app.table')

@section('content')
    <!-- Main content -->
    <x-section class="content">
        <div class="card card-primary card-outline">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#students" data-toggle="tab"><i
                                class="fa fa-users"></i>
                            Room Students</a></li>
                    <li class="nav-item"><a class="nav-link" href="#beds" data-toggle="tab"><i class="fa fa-list-alt"></i>
                            Bed Spaces </a></li>

                </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
                <div class="tab-content">

                    <div class="tab-pane active" id="students">
                        <!-- documents -->
                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fa fa-users"></i> Students ({{ count($students) }})</h3>
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
                    <div class="tab-pane" id="beds">
                        <!-- Add Room Label -->
                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fa fa-plus-circle"></i> Add Bed Space</h3>
                            </div>
                            <div class="card-body">
                                <x-form.form action="{{ route('hostel.rooms.save-space') }}" method="post"
                                    buttonName="Add Bed Space" buttonIcon="fa-plus" buttonClass="btn-info">
                                    <input type="hidden" name="room_id" value="{{ $room->room_id }}">

                                    <x-form.input class="col-md-12 col-sm-12" label="Bed Space label" type="text"
                                        name="space_label" placeholder="Bed Space label e.g A" value="" />

                                </x-form.form>
                                <hr>
                                <x-table.table id="">
                                    <x-table.thead>
                                        <th>S.N</th>
                                        <th>ROOM LABEL</th>
                                        <th>SPACE LABEL</th>
                                        <th>OCCUPANTS</th>
                                        <th>Action</th>
                                    </x-table.thead>
                                    <tbody>
                                        @foreach ($beds as $bed)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $bed->room_label }}</td>
                                                <td>{{ $bed->space_label }}</td>
                                                <td>{{ $bed->student }}</td>
                                                <td>
                                                    <x-buttons.delete
                                                        action="{{ route('hostel.rooms.delete-space', $bed->bed_id) }}"
                                                        btnSize="btn-xs" />
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </x-table.table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.Add Room Label -->
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
