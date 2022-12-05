@extends('layouts.app.table')

@section('content')
    <!-- Main content -->
    <x-section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#student" data-toggle="tab"><i
                                        class="fa fa-user-edit"></i> Student Details</a></li>
                            <li class="nav-item"><a class="nav-link" href="#subjects" data-toggle="tab"><i
                                        class="fa fa-graduation-cap"></i> Subjects</a></li>
                            <li class="nav-item"><a class="nav-link" href="#parents" data-toggle="tab"><i
                                        class="fa fa-users"></i> Student Parents</a></li>
                            <li class="nav-item"><a class="nav-link" href="#documents" data-toggle="tab"><i
                                        class="fa fa-list"></i>
                                    Documents</a></li>
                            <li class="nav-item"><a class="nav-link" href="#hostel-details" data-toggle="tab"><i
                                        class="fa fa-list-alt"></i>
                                    Hostel Details</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="student">
                                <!-- Profile -->
                                <div class="row">
                                    <div class="col-md-3">
                                        <x-card class="card-secondary" icon="fa-user" title="Photo">
                                            <div class="box-profile">
                                                <div class="text-center">
                                                    <img class="profile-user-img img-fluid img-circle"
                                                        src="{{ asset('storage/assets/dist/img/users/' . $user->student_image) }}"
                                                        alt="{{ $user->name }} Profile picture">
                                                </div>
                                                <h3 class="profile-username text-center">{{ $user->name }}</h3>

                                                <p class="text-muted text-center">{{ $user->email }}</p>

                                                <ul class="list-group list-group-unbordered mb-3">
                                                    <li class="list-group-item">
                                                        <b>ADM NO. </b> <a class="float-right">{{ $user->admission_no }}</a>
                                                    </li>
                                                </ul>
                                                <form action="{{ route('students.update-photo', $user->student_id) }}"
                                                    method="post" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <input required type="file" name="student_image">
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="btn btn-secondary">
                                                        <i class="fa fa-edit"></i>
                                                        Update Photo</button>
                                                </form>
                                            </div>
                                        </x-card>
                                    </div>
                                    <div class="col-md-9">
                                        <x-card class="card-secondary" icon="fa-user-edit" :title="$title">
                                            <x-form.form action="{{ route('students.students.update', $user->id) }}"
                                                method="put" buttonName="Update Student Data" buttonIcon="fa-user-edit"
                                                buttonClass="btn-secondary">

                                                <x-form.input class="col-md-4 col-sm-6" label="Student full name"
                                                    type="text" name="name" placeholder="Student full name"
                                                    value="{{ $user->name }}" />

                                                <x-form.input class="col-md-4 col-sm-6" label="Admission number"
                                                    type="text" name="admission_no" placeholder="Admission number"
                                                    value="{{ $user->admission_no }}" readonly />

                                                <x-form.input class="col-md-4 col-sm-6" label="Date Of Admission"
                                                    type="date" name="admission_date"
                                                    placeholder="Select date of admission"
                                                    value="{{ date_format(date_create($user->birth_date), 'Y-m-d') }}" />

                                                <x-form.select class="col-md-4 col-sm-6"
                                                    value="{{ $user->section_numeric }}" label="Student Class"
                                                    name="section_numeric">
                                                    @foreach ($forms as $form)
                                                        <option value="{{ $form->form_numeric }}">
                                                            {{ $form->form_name }}</option>
                                                    @endforeach
                                                </x-form.select>

                                                <x-form.select class="col-md-4 col-sm-6" value="" label="Sections"
                                                    name="section">
                                                    <option selected value="{{ $user->section_id }}">
                                                        {{ $user->section_numeric . $user->section_name }}
                                                    </option>
                                                </x-form.select>

                                                <x-form.input class="col-md-4 col-sm-6" label="Student UPI" type="number"
                                                    name="upi" placeholder="Student UPI"
                                                    value="{{ $user->upi }}" />

                                                <x-form.select class="col-md-4 col-sm-6" value="{{ $user->gender }}"
                                                    label="Gender" name="gender">
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                </x-form.select>

                                                <x-form.input class="col-md-4 col-sm-6" label="Date Of Birth"
                                                    type="date" name="birth_date" placeholder="Select date of birth"
                                                    value="{{ date_format(date_create($user->birth_date), 'Y-m-d') }}" />

                                                <x-form.input class="col-md-4 col-sm-6" label="Address" type="text"
                                                    name="address" placeholder="Address" value="{{ $user->address }}" />

                                                <x-form.input class="col-md-4 col-sm-6" label="Physical Challenge"
                                                    type="text" name="impaired" placeholder="Physical challenge"
                                                    value="{{ $user->impaired }}" />

                                                <x-form.select class="col-md-4 col-sm-6" value="{{ $user->county_id }}"
                                                    label="County" name="county" id="county">
                                                    @foreach ($counties as $county)
                                                        <option value="{{ $county->county_id }}">
                                                            {{ $county->county_name }}</option>
                                                    @endforeach
                                                </x-form.select>

                                                <x-form.select class="col-md-4 col-sm-6" value="{{ $user->sub_county }}"
                                                    label="Sub County" name="sub_county" />

                                                <x-form.input class="col-md-4 col-sm-6" label="Ward" type="text"
                                                    name="ward" placeholder="Ward" value="{{ $user->ward }}" />

                                                <x-form.input class="col-md-4 col-sm-6" label="Co-Curricular"
                                                    type="text" name="extra" placeholder="Co curricular activities"
                                                    value="{{ $user->extra }}" />

                                                <x-form.input class="col-md-4 col-sm-6" label="Primary School"
                                                    type="text" name="primary_school" placeholder="Primary School"
                                                    value="{{ $user->primary_school }}" />

                                                <x-form.select class="col-md-4 col-sm-6" value="{{ $user->kcpe_year }}"
                                                    label="KCPE Year" name="kcpe_year">
                                                    @for ($i = 2005; $i < 2024; $i++)
                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                    @endfor
                                                </x-form.select>

                                                <x-form.input class="col-md-4 col-sm-6" label="KCPE Marks" type="number"
                                                    name="kcpe_marks" placeholder="KCPE Marks"
                                                    value="{{ $user->kcpe_marks }}" />

                                                <x-form.select class="col-md-4 col-sm-6" value="{{ $user->religion }}"
                                                    label="Religion" name="religion">
                                                    <option value="Christian">Christian</option>
                                                    <option value="Islamist">Islamist</option>
                                                    <option value="Budhist">Budhist</option>
                                                    <option value="Pagan">Pagan</option>
                                                    <option value="Others">Others</option>
                                                </x-form.select>
                                            </x-form.form>
                                        </x-card>
                                        <!-- /.Profile -->
                                    </div>
                                </div>

                            </div>
                            <!-- /.tab-pane -->

                            <div class="tab-pane" id="subjects">
                                <!-- subjects -->
                                <div class="card card-info">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="fa fa-graduation-cap"></i> Subjects</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 col-sm-12">
                                                <x-form.form action="{{ route('students.store-subjects') }}"
                                                    method="post" buttonName="Assign Subjects" buttonIcon="fa-user-edit"
                                                    buttonClass="btn-secondary">
                                                    <input type="hidden" name="student_id" value="{{ $user->id }}">
                                                    <div class="col-m-12">
                                                        <div class="form-group">
                                                            @foreach ($subjects as $subject)
                                                                <div class="form-check">
                                                                    @if (count($s_subjects) != 0)
                                                                        <input class="form-check-input" type="checkbox"
                                                                            name="subjects[]"
                                                                            value="{{ $subject->subject_id }}"
                                                                            @if ($s_subjects->pluck('subject_id')->contains($subject->subject_id)) checked @endif>
                                                                    @else
                                                                        <input class="form-check-input" type="checkbox"
                                                                            name="subjects[]"
                                                                            value="{{ $subject->subject_id }}"
                                                                            @if ($subject->optionality == 'Compulsory') checked @endif>
                                                                    @endif
                                                                    <label
                                                                        class="form-check-label">{{ ucwords($subject->subject_name) }}</label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </x-form.form>
                                            </div>
                                            <div class="col-md-8 col-sm-12">
                                                @if (count($s_subjects) == 0)
                                                    <div class="alert alert-warning">
                                                        No subjects are assigned to this student
                                                    </div>
                                                @else
                                                    <table class="table table-sm table-striped table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>S.N</th>
                                                                <th width="50%">Name</th>
                                                                <th>Abbr.</th>
                                                                <th>Code</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($s_subjects as $subject)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td><strong>{{ $subject->subject_name }}</strong></td>
                                                                    <td>{{ $subject->subject_short }}</td>
                                                                    <td>{{ $subject->subject_code }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- subjects -->
                            </div>
                            <!-- /.tab-pane -->

                            <div class="tab-pane" id="parents">
                                <div class="row">
                                    <div class="col-md-7 col-sm-6">
                                        <!-- parents -->
                                        <div class="card card-secondary">
                                            <div class="card-header">
                                                <h3 class="card-title"><i class="fa fa-user-plus"></i> Add New Parent</h3>
                                            </div>
                                            <div class="card-body">
                                                <x-form.form action="{{ route('students.parents.store') }}"
                                                    method="post" buttonName="Add New Parent" buttonIcon="fa-user-plus"
                                                    buttonClass="btn-secondary">
                                                    <input type="hidden" name="has_student" value="1">
                                                    <input type="hidden" name="student_id"
                                                        value="{{ $user->student_id }}">

                                                    <x-form.input class="col-sm-6" label="Full name" type="text"
                                                        name="name" placeholder="Full name" value="" />

                                                    <x-form.input class="col-sm-6" label="Address" type="address"
                                                        name="address" placeholder="Address" value="" />

                                                    <x-form.input class="col-sm-6" label="Mobile no" type="number"
                                                        name="mobile_no" placeholder="Mobile no" value=""
                                                        onKeyPress="if(this.value.length==10) return false;"
                                                        minlength="10" maxlength="10" />

                                                    <x-form.select class="col-sm-6" value="" label="Gender"
                                                        name="gender">
                                                        <option value="Male">Male</option>
                                                        <option value="Female">Female</option>
                                                    </x-form.select>

                                                    <x-form.select class="col-sm-6" value="" label="Type"
                                                        name="type">
                                                        <option value="Mother">Mother</option>
                                                        <option value="Father">Father</option>
                                                        <option value="Guardian">Guardian</option>
                                                        <option value="Sibling">Sibling</option>
                                                        <option value="Others">Others</option>
                                                    </x-form.select>

                                                    <x-form.input class="col-sm-6" label="Profession" type="text"
                                                        name="profession" placeholder="Profession" value="" />

                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="receive_sms">Receive SMS</label>
                                                            <select name="receive_sms" id="receive_sms"
                                                                class="form-control">
                                                                <option value="1">No</option>
                                                                <option value="0">Yes</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </x-form.form>
                                            </div>
                                            <!-- /.card-body -->
                                        </div>
                                        <!-- parents -->
                                    </div>
                                    <div class="col-md-5 col-sm-6">
                                        <!-- parents -->
                                        <div class="card card-warning">
                                            <div class="card-header">
                                                <h3 class="card-title"><i class="fa fa-user-edit"></i> Add Existing Parent
                                                </h3>
                                            </div>
                                            <div class="card-body">
                                                <x-form.form action="{{ route('students.parents.add') }}" method="post"
                                                    buttonName="Add Existing Parent" buttonIcon="fa-user-plus"
                                                    buttonClass="btn-warning">
                                                    <input type="hidden" name="student_id"
                                                        value="{{ $user->student_id }}">

                                                    <x-form.select class="col-md-12" value="" label="Select Parent"
                                                        name="parent">
                                                        @foreach ($parents as $parent)
                                                            <option value="{{ $parent->parent_id }}">{{ $parent->name }}
                                                                [{{ $parent->mobile_no }}]</option>
                                                        @endforeach
                                                    </x-form.select>
                                                </x-form.form>
                                            </div>
                                            <!-- /.card-body -->
                                        </div>
                                        <!-- parents -->
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <x-card class="card-info" icon="fa-users" title="Student Parents">
                                            <x-table.table id="table1">
                                                <x-table.thead>
                                                    <th>S.N</th>
                                                    <th>TYPE</th>
                                                    <th>NAMES</th>
                                                    <th>PHONE</th>
                                                    <th>SMS</th>
                                                    <th>PROFESSION</th>
                                                    <th>ADDRESS</th>
                                                    <th>ACTIONS</th>
                                                </x-table.thead>
                                                <tbody>
                                                    @foreach ($s_parents as $parent)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $parent->type }}</td>
                                                            <td>{{ $parent->name }}</td>
                                                            <td>{{ $parent->mobile_no }}</td>
                                                            <td>{{ $parent->receive_sms ? 'Yes' : 'No' }}</td>
                                                            <td>{{ $parent->profession }}</td>
                                                            <td>{{ $parent->address }}</td>
                                                            <td>
                                                                <a
                                                                    href="{{ route('students.parents.edit', $parent->parent_id) }}">
                                                                    <x-buttons.button class="btn-primary btn-xs"
                                                                        buttonName="Edit" buttonIcon="fa-edit" />
                                                                </a>
                                                                <a
                                                                    href="{{ route('students.parents.show', $parent->parent_id) }}">
                                                                    <x-buttons.button class="btn-info btn-xs"
                                                                        buttonName="Show" buttonIcon="fa-bars" />
                                                                </a>
                                                                <x-buttons.delete
                                                                    action="{{ route('students.parents.destroy', $parent->parent_id) }}"
                                                                    btnSize="btn-xs" />
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </x-table.table>
                                        </x-card>
                                    </div>
                                </div>
                            </div>
                            <!-- /.tab-pane -->

                            <div class="tab-pane" id="documents">
                                <!-- documents -->
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="fa fa-graduation-cap"></i> Subjects</h3>
                                    </div>
                                    <div class="card-body">
                                        Documents
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- documents -->
                            </div>
                            <!-- /.tab-pane -->

                            <div class="tab-pane" id="hostel-details">
                                <!-- documents -->
                                <div class="card card-info">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="fa fa-list-alt"></i> Hostel Details</h3>
                                    </div>
                                    <div class="card-body">
                                        <x-form.form action="{{ route('hostel.rooms.assign') }}" method="post"
                                            buttonName="Assign Student Room" buttonIcon="fa-user-plus"
                                            buttonClass="btn-secondary">
                                            <input type="hidden" name="student_id" value="{{ $user->student_id }}">
                                            @if ($student_room)
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>Current Hostel</label>
                                                        <input type="text" class="form-control" readonly
                                                            value="{{ $student_room->hostel_name }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>Current Room</label>
                                                        <input type="text" class="form-control" readonly
                                                            value="{{ $student_room->room_label }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>Current Bed Space</label>
                                                        <input type="text" class="form-control" readonly
                                                            value="{{ $student_room->space_label }}" required>
                                                    </div>
                                                </div>
                                            @endif
                                            <x-form.select class="col-sm-4" value="" label="Hostel"
                                                name="hostel">
                                                @foreach ($hostels as $hostel)
                                                    <option value="{{ $hostel->hostel_id }}">
                                                        {{ $hostel->hostel_name }}
                                                    </option>
                                                @endforeach
                                            </x-form.select>

                                            <x-form.select class="col-sm-4" value="" label="Room"
                                                name="room">
                                            </x-form.select>

                                            <x-form.select class="col-sm-4" value="" label="Bed Space"
                                                name="beds">
                                            </x-form.select>
                                        </x-form.form>
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
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </x-section>
    <!-- /.section component -->
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#county').change(function() {
                county = $('#county').val();
                if (county != '') {
                    $.ajax({
                        url: "{{ route('admin.get.subcounties') }}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        data: {
                            county: county
                        },
                        success: function(data) {
                            console.log(data);
                            $('#sub_county').html(data);
                        },
                        error: function(xhr, desc, err) {
                            console.log(xhr);
                            //console.log("Details0: " + desc + "\nError:" + err);
                        },
                    });
                } else {
                    $('#sub_county').html('<option value="">Select Sub County</option>');
                }
            });

            $('#section_numeric').change(function() {
                section_numeric = $('#section_numeric').val();
                if (section_numeric != '') {
                    $.ajax({
                        url: "{{ route('get.formsections') }}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        data: {
                            section_numeric: section_numeric
                        },
                        success: function(data) {
                            console.log(data);
                            $('#section').html(data);
                        },
                        error: function(xhr, desc, err) {
                            console.log(xhr);
                            //console.log("Details0: " + desc + "\nError:" + err);
                        },
                    });
                } else {
                    $('#section').html('<option value="">Select Form First</option>');
                }
            });

            $('#hostel').change(function() {
                hostel_id = $('#hostel').val();
                if (hostel_id != '') {
                    $.ajax({
                        url: "{{ route('hostel.hostels.get-rooms') }}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        data: {
                            hostel_id: hostel_id
                        },
                        success: function(data) {
                            console.log(data);
                            $('#room').html(data);
                        },
                        error: function(xhr, desc, err) {
                            console.log(xhr);
                            //console.log("Details0: " + desc + "\nError:" + err);
                        },
                    });
                } else {
                    $('#room').html('<option value="">No Rooms Available</option>');
                }
            });

            $('#room').change(function() {
                room_id = $('#room').val();
                if (room_id != '') {
                    $.ajax({
                        url: "{{ route('hostel.rooms.fetch-spaces') }}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        data: {
                            room_id: room_id
                        },
                        success: function(data) {
                            console.log(data);
                            $('#beds').html(data);
                        },
                        error: function(xhr, desc, err) {
                            console.log(xhr);
                            //console.log("Details0: " + desc + "\nError:" + err);
                        },
                    });
                } else {
                    $('#beds').html('<option value="">No Spaces Available</option>');
                }
            });
        });
    </script>
@endpush
