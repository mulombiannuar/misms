@extends('layouts.app.form')

@section('content')
    <!-- Main content -->
    <x-section class="content">
        <x-card class="card-primary" icon="fa-edit" :title="$title">
            <x-form.form action="{{ route('admin.periods.update', $period->period_id) }}" method="patch"
                buttonName="Update Period" buttonIcon="fa-edit" buttonClass="btn-secondary">

                <x-form.input class="col-md-4 col-sm-12" label="Period" type="text" name="period"
                    placeholder="Enter period name" value="{{ $period->period_name }}" readonly />

                <x-form.input class="col-md-4 col-sm-12" label="Starts at" type="time" name="start_time"
                    placeholder="Enter start time" value="{{ date('H:i', strtotime($period->start_time)) }}" />

                <x-form.input class="col-md-4 col-sm-12" label="Ends at" type="time" name="end_time"
                    placeholder="Enter end time" value="{{ date('H:i', strtotime($period->end_time)) }}" />
            </x-form.form>
        </x-card>
    </x-section>
    <!-- /.section component -->
@endsection
