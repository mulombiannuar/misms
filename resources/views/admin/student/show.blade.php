@php
    $user_image = $user->gender == 'male' ? 'icon-male.png' : 'icon-female.png';
@endphp
@extends('layouts.app.form')

@section('content')
    <!-- Main content -->
    <x-section class="content">
        <div class="row">
            <div class="col-md-3">
                <x-card class="card-primary card-outline" icon="fa-user" title="Profile Photo">
                    <div class="box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle"
                                src="{{ asset('assets/dist/img/users/' . $user_image) }}"
                                alt="{{ $user->name }} Profile picture">
                        </div>
                        <h3 class="profile-username text-center">{{ $user->name }}</h3>

                        <p class="text-muted text-center">{{ $user->email }}</p>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>MOBILE </b> <a class="float-right">{{ $user->mobile_no }}</a>
                            </li>
                        </ul>
                    </div>
                </x-card>
            </div>
            <div class="col-md-9">
                <x-card class="card-primary" icon="fa-user-plus" title="Profile Data">
                    <div class="row">
                        <x-form.input class="col-md-4 col-sm-6" label="Email*" type="email" name="email"
                            placeholder="Email addresss" value="{{ $user->email }}" />

                        <x-form.input class="col-md-4 col-sm-6" label="Full name*" type="text" name="name"
                            placeholder="Full name" value="{{ $user->name }}" />

                        <x-form.input class="col-md-4 col-sm-6" label="National ID*" type="number" name="national_id"
                            placeholder="National ID" value="{{ $user->national_id }}" />

                        <x-form.input class="col-md-4 col-sm-6" label="Mobile no*" type="number" name="mobile_no"
                            placeholder="Mobile no" value="{{ $user->mobile_no }}"
                            onKeyPress="if(this.value.length==10) return false;" minlength="10" maxlength="10" />

                        <x-form.input class="col-md-4 col-sm-6" label="Gender*" type="text" name="gender"
                            placeholder="Gender" value="{{ ucwords($user->gender) }}" />

                        <x-form.input class="col-md-4 col-sm-6" label="Date Of Birth*" type="date" name="birth_date"
                            placeholder="Select date of birth"
                            value="{{ date_format(date_create($user->birth_date), 'Y-m-d') }}" />

                        <x-form.input class="col-md-4 col-sm-6" label="Address*" type="text" name="address"
                            placeholder="Address" value="{{ $user->address }}" />

                        <x-form.input class="col-md-4 col-sm-6" label="County*" type="text" name="county"
                            placeholder="County" value="{{ $user->county_name }}" />

                        <x-form.input class="col-md-4 col-sm-6" label="Sub County*" type="text" name="sub_county"
                            placeholder="County" value="{{ $user->sub_name }}" />

                        <x-form.input class="col-md-4 col-sm-6" label="Religion*" type="text" name="religion"
                            placeholder="Religion" value="{{ $user->religion }}" />
                    </div>
                </x-card>
            </div>
        </div>
    </x-section>
    <!-- /.section component -->
@endsection
