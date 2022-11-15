@extends('layouts.app.form')

@section('content')
    <!-- Main content -->
    <x-section class="content">
        <x-card class="card-primary" icon="fa-user-plus" :title="$title">
            <x-form.form action="{{ route('admin.users.store') }}" method="post" buttonName="Add New User"
                buttonIcon="fa-user-plus" buttonClass="btn-primary">
                <input type="hidden" name="is_student" value="0">
                <input type="hidden" name="password" value="#1547TmnT0JHDPCV">
                <input type="hidden" name="password_confirmation" value="#1547TmnT0JHDPCV">

                <x-form.input class="col-md-4 col-sm-6" label="Email*" type="email" name="email"
                    placeholder="Email addresss" value="" />

                <x-form.input class="col-md-4 col-sm-6" label="Full name*" type="text" name="name"
                    placeholder="Full name" value="" />

                <x-form.input class="col-md-4 col-sm-6" label="National ID*" type="number" name="national_id"
                    placeholder="National ID" value="" />

                <x-form.input class="col-md-4 col-sm-6" label="Mobile no*" type="number" name="mobile_no"
                    placeholder="Mobile no" value="" onKeyPress="if(this.value.length==10) return false;"
                    minlength="10" maxlength="10" />

                <x-form.select class="col-md-4 col-sm-6" value="" label="Gender*" name="gender">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </x-form.select>

                <x-form.input class="col-md-4 col-sm-6" label="Date Of Birth*" type="date" name="birth_date"
                    placeholder="Select date of birth"
                    value="{{ date_format(date_create('1990-01-01 10:35:10'), 'Y-m-d') }}" />

                <x-form.input class="col-md-4 col-sm-6" label="Address*" type="text" name="address" placeholder="Address"
                    value="" />

                <x-form.select class="col-md-4 col-sm-6" value="" label="County*" name="county" id="county">
                    @foreach ($counties as $county)
                        <option value="{{ $county->county_id }}">
                            {{ $county->county_name }}</option>
                    @endforeach
                </x-form.select>

                <x-form.select class="col-md-4 col-sm-6" value="" label="Sub County*" name="sub_county" />

                <x-form.select class="col-md-4 col-sm-6" value="" label="Religion*" name="religion">
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

        });
    </script>
@endpush
