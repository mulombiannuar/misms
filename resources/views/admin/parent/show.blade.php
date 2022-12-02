@extends('layouts.app.form')

@section('content')
    <!-- Main content -->
    <x-section class="content">
        <div class="card card-warning card-outline">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#details" data-toggle="tab"><i class="fa fa-user"></i>
                            Parents Details</a></li>
                    <li class="nav-item"><a class="nav-link" href="#students" data-toggle="tab"><i class="fa fa-users"></i>
                            Parent Students</a></li>
                </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="details">
                        <!-- Profile -->
                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fa fa-user"></i> Parents Details</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Parent Type</label>
                                            <input type="text" class="form-control" value="{{ $parent->type }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="exampleInputText1">Name</label>
                                            <input type="text" class="form-control" value="{{ $parent->name }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Address</label>
                                            <input type="text" class="form-control" value="{{ $parent->address }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="exampleInputText1">Profession</label>
                                            <input type="text" n class="form-control" value="{{ $parent->profession }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="exampleInputText1">Gender</label>
                                            <input type="text" n class="form-control"
                                                value="{{ ucwords($parent->gender) }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="exampleInputText1">Mobile</label>
                                            <input type="text" class="form-control" value="{{ $parent->mobile_no }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="exampleInputText1">Receives SMS</label>
                                            <input type="text" n class="form-control"
                                                value="{{ $parent->receive_sms ? 'Yes' : 'No' }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.Profile -->
                    <div class="tab-pane" id="students">
                        <!-- documents -->
                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fa fa-users"></i> Parent's Students</h3>
                            </div>
                            <div class="card-body">
                                <x-table.table id="table1">
                                    <x-table.thead>
                                        <th>S.N</th>
                                        <th>ADM. NO</th>
                                        <th>NAMES</th>
                                        <th>GENDER</th>
                                        <th>CLASS</th>
                                        <th>KCPE</th>
                                        <th>ADM DATE</th>
                                        <th>ACTIONS</th>
                                    </x-table.thead>
                                    <tbody>
                                        @foreach ($parent->students as $student)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td><strong>{{ $student->admission_no }}</strong></td>
                                                <td>{{ $student->user->name }}</td>
                                                <td>{{ $student->gender }}</td>
                                                <td>{{ $student->section->section_numeric . $student->section->section_name }}
                                                </td>
                                                <td>{{ $student->kcpe_marks }}</td>
                                                <td>{{ $student->admission_date }}</td>
                                                <td>
                                                    <a
                                                        href="{{ route('students.students.show', $student->student_user_id) }}">
                                                        <x-buttons.button class="btn-info btn-xs" buttonName="Show"
                                                            buttonIcon="fa-bars" />
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </x-table.table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- documents -->
                    </div>
                    <!-- /.tab-pane -->
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
