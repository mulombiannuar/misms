@extends('layouts.app.form')

@section('content')
    <!-- Main content -->
    <x-section class="content">
        <x-card class="card-primary" icon="fa-user-plus" :title="$title">
            <x-form.form action="{{ route('students.students.store') }}" method="post" buttonName="Add New Student"
                buttonIcon="fa-user-plus" buttonClass="btn-primary">
                <input type="hidden" name="email" value="{{ strtolower(date('dmyHis') . '@mulan.co.ke') }}">
                <input type="hidden" name="is_student" value="1">
                <input type="hidden" name="password" value="#1547TmnT0JHDPCV">
                <input type="hidden" name="password_confirmation" value="#1547TmnT0JHDPCV">

                <x-form.input class="col-md-4 col-sm-6" label="Student full name" type="text" name="name"
                    placeholder="Student full name" value="" />

                <x-form.input class="col-md-4 col-sm-6" label="Admission number" type="text" name="admission_no"
                    placeholder="Admission number" value="" />

                <x-form.input class="col-md-4 col-sm-6" label="Date Of Admission" type="date" name="admission_date"
                    placeholder="Select date of admission" value="{{ date_format(date_create(now()), 'Y-m-d') }}" />

                <x-form.select class="col-md-4 col-sm-12" value="" label="Student Class" name="section_numeric">
                    @foreach ($forms as $form)
                        <option value="{{ $form->form_numeric }}">
                            {{ $form->form_name }}</option>
                    @endforeach
                </x-form.select>

                <x-form.select class="col-md-4 col-sm-12" value="" label="Sections" name="section">
                    <option value="">- Select form first -</option>
                </x-form.select>

                <x-form.input class="col-md-4 col-sm-6" label="Student UPI" type="number" name="upi"
                    placeholder="Student UPI" value="" />

                <x-form.select class="col-md-4 col-sm-6" value="" label="Gender" name="gender">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </x-form.select>

                <x-form.input class="col-md-4 col-sm-6" label="Date Of Birth" type="date" name="birth_date"
                    placeholder="Select date of birth"
                    value="{{ date_format(date_create('2005-01-01 10:35:10'), 'Y-m-d') }}" />

                <x-form.input class="col-md-4 col-sm-6" label="Address" type="text" name="address" placeholder="Address"
                    value="" />

                <x-form.input class="col-md-4 col-sm-6" label="Physical Challenge" type="text" name="impaired"
                    placeholder="Physical challenge" value="" />

                <x-form.select class="col-md-4 col-sm-6" value="" label="County" name="county" id="county">
                    @foreach ($counties as $county)
                        <option value="{{ $county->county_id }}">
                            {{ $county->county_name }}</option>
                    @endforeach
                </x-form.select>

                <x-form.select class="col-md-4 col-sm-6" value="" label="Sub County" name="sub_county" />

                <x-form.input class="col-md-4 col-sm-6" label="Ward" type="text" name="ward" placeholder="Ward"
                    value="" />

                <x-form.input class="col-md-4 col-sm-6" label="Co-Curricular" type="text" name="extra"
                    placeholder="Co curricular activities" value="" />

                <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                        <label for="student_image">Student Image</label>
                        <input type="file" name="student_image" class="form-control" id="student_image">
                    </div>
                </div>

                <x-form.input class="col-md-4 col-sm-6" label="Primary School" type="text" name="primary_school"
                    placeholder="Primary School" value="" />

                <x-form.select class="col-md-4 col-sm-6" value="" label="KCPE Year" name="kcpe_year">
                    @for ($i = 2005; $i < 2024; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </x-form.select>

                <x-form.input class="col-md-4 col-sm-6" label="KCPE Marks" type="number" name="kcpe_marks"
                    placeholder="KCPE Marks" value="" />

                <x-form.select class="col-md-4 col-sm-6" value="" label="Religion" name="religion">
                    <option value="Christian">Christian</option>
                    <option value="Islamist">Islamist</option>
                    <option value="Budhist">Budhist</option>
                    <option value="Pagan">Pagan</option>
                    <option value="Others">Others</option>
                </x-form.select>

            </x-form.form>
        </x-card>
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
