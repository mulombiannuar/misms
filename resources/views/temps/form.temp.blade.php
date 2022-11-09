@extends('layouts.app.form')

@section('content')
    <!-- Main content -->
    <x-section class="content">
        <x-card class="card-secondary" icon="fa-unlock" :title="$title">
            <x-form.form action="{{ route('admin.periods.store') }}" method="post" buttonName="Save New Period"
                buttonIcon="fa-plus" buttonClass="btn-primary">

                <x-form.input class="col-md-4 col-sm-12" label="Current Password" type="password" name="current_password"
                    placeholder="Enter current password" value="" />
            </x-form.form>
        </x-card>
    </x-section>
    <!-- /.section component -->
@endsection
