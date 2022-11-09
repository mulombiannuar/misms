@extends('layouts.app.form')

@section('content')
    <!-- Main content -->
    <x-section class="content">
        <x-card class="card-primary" icon="fa-calendar" :title="$title">
            <x-form.form action="{{ route('admin.sessions.store') }}" method="post" buttonName="Save New Sessions"
                buttonIcon="fa-plus" buttonClass="btn-primary">

                <x-form.select class="col-md-6 col-sm-12" value="" label="Session" name="session">
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                </x-form.select>

                <x-form.select class="col-md-6 col-sm-12" value="" label="Term" name="term">
                    <option value="1">Term 1</option>
                    <option value="2">Term 2</option>
                    <option value="3">Term 3</option>
                </x-form.select>

                <x-form.input class="col-md-6 col-sm-12" label="Opening Date" type="date" name="opening_date"
                    placeholder="Select opening date" value="{{ date('Y-m-d') }}" />

                <x-form.input class="col-md-6 col-sm-12" label="Closing Date" type="date" name="closing_date"
                    placeholder="Select closing date" value="{{ date('Y-m-d') }}" />
            </x-form.form>
        </x-card>
    </x-section>
    <!-- /.section component -->
@endsection
