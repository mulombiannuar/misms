@extends('layouts.app.form')

@section('content')
    <!-- Main content -->
    <x-section class="content">
        <x-card class="card-primary" icon="fa-calendar" :title="$title">
            <x-form.form action="{{ route('admin.periods.store') }}" method="post" buttonName="Save New Period"
                buttonIcon="fa-plus" buttonClass="btn-primary">

                <x-form.select class="col-md-4 col-sm-12" value="" label="Period" name="period">
                    @for ($i = 1; $i < 16; $i++)
                        <option value="P{{ $i }}">P{{ $i }}</option>
                    @endfor
                </x-form.select>

                <x-form.input class="col-md-4 col-sm-12" label="Starts at" type="time" name="start_time"
                    placeholder="Enter start time" value="" />

                <x-form.input class="col-md-4 col-sm-12" label="Ends at" type="time" name="end_time"
                    placeholder="Enter end time" value="" />
            </x-form.form>
        </x-card>
    </x-section>
    <!-- /.section component -->
@endsection
