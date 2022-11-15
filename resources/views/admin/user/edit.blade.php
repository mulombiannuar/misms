@extends('layouts.app.form')

@section('content')
    <!-- Main content -->
    <x-section class="content">
        <x-card class="card-secondary" icon="fa-user-edit" :title="$title">
            <x-form.form action="{{ route('admin.users.update', $user->id) }}" method="put" buttonName="Update User Data"
                buttonIcon="fa-user-edit" buttonClass="btn-secondary">

                <x-form.input class="col-md-4 col-sm-6" label="Email*" type="email" name="email"
                    placeholder="Email addresss" value="{{ $user->email }}" disabled />

                <x-form.input class="col-md-4 col-sm-6" label="Full name*" type="text" name="name" placeholder="Full name"
                    value="{{ $user->name }}" />

                <x-form.input class="col-md-4 col-sm-6" label="National ID*" type="number" name="national_id"
                    placeholder="National ID" value="{{ $user->national_id }}" />

                <x-form.input class="col-md-4 col-sm-6" label="Mobile no*" type="number" name="mobile_no"
                    placeholder="Mobile no" value="{{ $user->mobile_no }}"
                    onKeyPress="if(this.value.length==10) return false;" minlength="10" maxlength="10" />

                <x-form.select class="col-md-4 col-sm-6" value="{{ $user->gender }}" label="Gender*" name="gender">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </x-form.select>

                <x-form.input class="col-md-4 col-sm-6" label="Date Of Birth*" type="date" name="birth_date"
                    placeholder="Select date of birth" value="{{ date_format(date_create($user->birth_date), 'Y-m-d') }}" />

                <x-form.input class="col-md-4 col-sm-6" label="Address*" type="text" name="address" placeholder="Address"
                    value="{{ $user->address }}" />

                <x-form.select class="col-md-4 col-sm-6" value="{{ $user->county }}" label="County*" name="county"
                    id="county">
                    @foreach ($counties as $county)
                        <option value="{{ $county->county_id }}">
                            {{ $county->county_name }}</option>
                    @endforeach
                </x-form.select>

                <x-form.select class="col-md-4 col-sm-6" value="{{ $user->sub_county }}" label="Sub County*"
                    name="sub_county" />

                <x-form.select class="col-md-4 col-sm-6" value="{{ $user->religion }}" label="Religion*" name="religion">
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
