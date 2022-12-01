@extends('layouts.app.form')

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
                                        class="fa fa-users"></i> Parents</a></li>
                            <li class="nav-item"><a class="nav-link" href="#documents" data-toggle="tab"><i
                                        class="fa fa-list"></i>
                                    Documents</a></li>
                            <li class="nav-item"><a class="nav-link" href="#hostel" data-toggle="tab"><i
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

                                                <x-form.select class="col-md-4 col-sm-12"
                                                    value="{{ $user->section_numeric }}" label="Student Class"
                                                    name="section_numeric">
                                                    @foreach ($forms as $form)
                                                        <option value="{{ $form->form_numeric }}">
                                                            {{ $form->form_name }}</option>
                                                    @endforeach
                                                </x-form.select>

                                                <x-form.select class="col-md-4 col-sm-12" value="" label="Sections"
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
                                        Subjects
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- subjects -->
                            </div>
                            <!-- /.tab-pane -->

                            <div class="tab-pane" id="parents">
                                <!-- parents -->
                                <div class="card card-warning">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="fa fa-users"></i>Student Parents</h3>
                                    </div>
                                    <div class="card-body">
                                        Parents
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- parents -->
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

                            <div class="tab-pane" id="hostel">
                                <!-- documents -->
                                <div class="card card-info">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="fa fa-list-alt"></i> Hostel Details</h3>
                                    </div>
                                    <div class="card-body">
                                        Hostel Details
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
        });
    </script>
@endpush
