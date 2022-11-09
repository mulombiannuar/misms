@extends('layouts.app.form')

@section('content')
    <!-- Main content -->
    <x-section class="content">
        <x-card class="card-secondary" icon="fa-unlock" :title="$title">
            <x-form.form action="{{ route('account.password.change') }}" method="put" buttonName="Update Password"
                buttonIcon="fa-edit" buttonClass="btn-secondary">

                <x-form.input class="col-md-4 col-sm-12" label="Current Password" type="password" name="current_password"
                    placeholder="Enter current password" value="" />

                <x-form.input class="col-md-4 col-sm-12" label="Password" type="password" name="password"
                    placeholder="Enter new password" value="" />

                <x-form.input class="col-md-4 col-sm-12" label="Confirm Password" type="password"
                    name="password_confirmation" placeholder="Confirm password" value="" />
            </x-form.form>
        </x-card>
    </x-section>
    <!-- /.section component -->
@endsection
