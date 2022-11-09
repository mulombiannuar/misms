@extends('layouts.app.form')

@section('content')
    <!-- Main content -->
    <x-section class="content">
        <x-card class="card-primary" icon="fa-cog" :title="$title">
            <x-form.form action="{{ route('admin.save-school') }}" method="post" buttonName="Save School Details"
                buttonIcon="fa-plus" buttonClass="btn-secondary">

                <x-form.input class="col-md-4 col-sm-12" value="{{ $school->name }}" label="School Name" type="text"
                    name="name" placeholder="School name" />

                <x-form.input class="col-md-4 col-sm-12" value="{{ $school->code }}" label="School Code" type="text"
                    name="code" placeholder="School code" />

                <x-form.input class="col-md-4 col-sm-12" value="{{ $school->email }}" label="Email address" type="email"
                    name="email" placeholder="Email address" />

                <x-form.input class="col-md-4 col-sm-12" value="{{ $school->telephone }}" label="Telephone" type="text"
                    name="telephone" placeholder="Telephone" />

                <x-form.input class="col-md-4 col-sm-12" value="{{ $school->domain }}" label="Domain address" type="text"
                    name="domain" placeholder="Domain address" />

                <x-form.input class="col-md-4 col-sm-12" value="{{ $school->motto }}" label="Motto" type="text" name="motto"
                    placeholder="Motto" />

                <x-form.input class="col-md-4 col-sm-12" value="{{ $school->address }}" label="Address" type="text"
                    name="address" placeholder="Address" />

                <x-form.input class="col-md-4 col-sm-12" value="{{ $school->principal }}" label="Principal" type="text"
                    name="principal" placeholder="Principal" />

                <x-form.select class="col-md-4 col-sm-12" value="{{ $school->category }}" label="Category" name="category">
                    <option value="Boarding School">Boarding School</option>
                    <option value="Day School">Day School</option>
                    <option value="Mixed School">Mixed School</option>
                </x-form.select>

                <x-form.input class="col-md-4 col-sm-12" value="" label="Logo" type="file" name="logo"
                    placeholder="Logo" />

            </x-form.form>
        </x-card>
    </x-section>
    <!-- /.section component -->
@endsection
