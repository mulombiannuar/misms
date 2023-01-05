@extends('layouts.app.form')

@section('content')
    <!-- Main content -->
    <x-section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#profile" data-toggle="tab"><i
                                        class="fa fa-user-edit"></i> Profile</a></li>
                            <li class="nav-item"><a class="nav-link" href="#roles" data-toggle="tab"><i
                                        class="fa fa-list-alt"></i>
                                    User Roles</a></li>
                            <li class="nav-item"><a class="nav-link" href="#subjects" data-toggle="tab"><i
                                        class="fa fa-graduation-cap"></i> Subjects</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="profile">
                                <!-- Profile -->
                                <x-card class="card-secondary" icon="fa-user-edit" :title="$title">
                                    <x-form.form action="{{ route('admin.users.update', $user->id) }}" method="put"
                                        buttonName="Update User Data" buttonIcon="fa-user-edit" buttonClass="btn-secondary">
                                        <x-form.input class="col-md-4 col-sm-6" label="Email*" type="email" name="email"
                                            placeholder="Email addresss" value="{{ $user->email }}" disabled />

                                        <x-form.input class="col-md-4 col-sm-6" label="Full name*" type="text"
                                            name="name" placeholder="Full name" value="{{ $user->name }}" />

                                        <x-form.input class="col-md-4 col-sm-6" label="Name initials" type="text"
                                            name="name_initial" placeholder="Name initials"
                                            value="{{ $user->name_initial }}" />

                                        <x-form.input class="col-md-4 col-sm-6" label="National ID*" type="number"
                                            name="national_id" placeholder="National ID" value="{{ $user->national_id }}" />

                                        <x-form.input class="col-md-4 col-sm-6" label="Mobile no*" type="number"
                                            name="mobile_no" placeholder="Mobile no" value="{{ $user->mobile_no }}"
                                            onKeyPress="if(this.value.length==10) return false;" minlength="10"
                                            maxlength="10" />

                                        <x-form.select class="col-md-4 col-sm-6" value="{{ $user->gender }}" label="Gender*"
                                            name="gender">
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                        </x-form.select>

                                        <x-form.input class="col-md-4 col-sm-6" label="Date Of Birth*" type="date"
                                            name="birth_date" placeholder="Select date of birth"
                                            value="{{ date_format(date_create($user->birth_date), 'Y-m-d') }}" />

                                        <x-form.input class="col-md-4 col-sm-6" label="Address*" type="text"
                                            name="address" placeholder="Address" value="{{ $user->address }}" />

                                        <x-form.select class="col-md-4 col-sm-6" value="{{ $user->county }}" label="County*"
                                            name="county" id="county">
                                            @foreach ($counties as $county)
                                                <option value="{{ $county->county_id }}">
                                                    {{ $county->county_name }}
                                                </option>
                                            @endforeach
                                        </x-form.select>

                                        <x-form.select class="col-md-4 col-sm-6" value="{{ $user->sub_county }}"
                                            label="Sub County*" name="sub_county" />

                                        <x-form.select class="col-md-4 col-sm-6" value="{{ $user->religion }}"
                                            label="Religion*" name="religion">
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
                            <!-- /.tab-pane -->

                            <div class="tab-pane" id="roles">
                                <!-- roles -->
                                <x-card class="card-warning" icon="fa-list-alt" title="User Roles">
                                    @if ($user->is_student)
                                        <div class="alert alert-danger">
                                            A student cannot be assigned other roles
                                        </div>
                                    @else
                                        <x-form.form action="{{ route('admin.users.storerole') }}" method="post"
                                            buttonName="Assign Selected Roles" buttonIcon="fa-user-edit"
                                            buttonClass="btn-warning">
                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    @foreach ($roles as $role)
                                                        @if ($role->name !== 'student')
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="roles[]" value="{{ $role->id }}"
                                                                    width="500px" height="500px"
                                                                    @if ($user_roles->pluck('role_id')->contains($role->id)) checked @endif>
                                                                <label
                                                                    class="form-check-label">{{ ucwords($role->name) }}</label>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </x-form.form>
                                    @endif

                                </x-card>
                                <!-- roles -->
                            </div>
                            <!-- /.tab-pane -->

                            <div class="tab-pane" id="subjects">
                                <!-- roles user -->
                                <div class="card card-info">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="fa fa-graduation-cap"></i> Subjects</h3>
                                    </div>
                                    <div class="card-body">
                                        <x-form.form action="{{ route('admin.subject-teachers.store') }}" method="post"
                                            buttonName="Assign New Subject" buttonIcon="fa-user-plus"
                                            buttonClass="btn-info">
                                            <input type="hidden" name="user_id" value="{{ $user->id }}">

                                            <x-form.select class="col-md-4 col-sm-6" value="" label="Class*"
                                                name="form_numeric" id="form_numeric">
                                                @foreach ($forms as $form)
                                                    <option value="{{ $form->form_numeric }}">
                                                        {{ $form->form_name }}</option>
                                                @endforeach
                                            </x-form.select>

                                            <x-form.select class="col-md-4 col-sm-6" value="" label="Sections*"
                                                name="section" name="section" />

                                            <x-form.select class="col-md-4 col-sm-6" value="" label="Subjects*"
                                                name="subject">
                                                @foreach ($subjects as $subject)
                                                    <option value="{{ $subject->subject_id }}">
                                                        {{ $subject->subject_name }}</option>
                                                @endforeach
                                            </x-form.select>

                                        </x-form.form>
                                        <div class="row">
                                            <div class="container">
                                                @if (count($a_subjects) == 0)
                                                    <div class="alert alert-danger">
                                                        <i class="fa fa-exclamation-circle"></i> You dont have any subject
                                                        assigned to you. Kindly contact system
                                                        administrator
                                                    </div>
                                                @else
                                                    <x-table.table id="table1">
                                                        <x-table.thead>
                                                            <th>S.N</th>
                                                            <th>Code</th>
                                                            <th>Name</th>
                                                            <th>Class</th>
                                                            <th>Date Created</th>
                                                            <th>Actions</th>
                                                        </x-table.thead>
                                                        <tbody>
                                                            @foreach ($a_subjects as $subject)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $subject->subject_code }}</td>
                                                                    <td>{{ $subject->subject_name }}</td>
                                                                    <td>{{ $subject->section_numeric . $subject->section_name }}
                                                                    </td>
                                                                    <td>{{ $subject->created_at }}</td>
                                                                    <td>
                                                                        <x-buttons.delete
                                                                            action="{{ route('admin.subject-teachers.destroy', $subject->sub_id) }}"
                                                                            btnSize="btn-xs" />
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </x-table.table>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- roles user -->
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

            $('#form_numeric').change(function() {
                section_numeric = $('#form_numeric').val();
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
