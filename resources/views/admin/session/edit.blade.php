@extends('layouts.app.form')

@section('content')
    <!-- Main content -->
    <x-section class="content">
        <x-card class="card-primary" icon="fa-edit" :title="$title">
            <x-form.form action="{{ route('admin.sessions.update', $session->session_id) }}" method="put"
                buttonName="Update Session" buttonIcon="fa-edit" buttonClass="btn-secondary">

                <x-form.input class="col-md-6 col-sm-12" label="Session" type="text" name="session"
                    placeholder="Enter session" value="{{ $session->session }}" readonly />

                <x-form.input class="col-md-6 col-sm-12" label="Term" type="text" name="term" placeholder="Enter term"
                    value="{{ 'Term ' . $session->term }}" readonly />

                <x-form.input class="col-md-6 col-sm-12" label="Opening Date ({{ $session->opening_date }})" type="date"
                    name="opening_date" placeholder="Select opening date" value="{{ $session->opening_date }}" />

                <x-form.input class="col-md-6 col-sm-12" label="Closing Date ({{ $session->closing_date }})" type="date"
                    name="closing_date" placeholder="Select closing date" value="{{ $session->closing_date }}" />
            </x-form.form>
        </x-card>
    </x-section>
    <!-- /.section component -->
@endsection
