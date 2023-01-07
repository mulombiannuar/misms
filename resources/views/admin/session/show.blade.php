@extends('layouts.app.form')

@section('content')
    <!-- Main content -->
    <x-section class="content">
        <x-card class="card-primary" icon="fa-calendar" :title="$title">
            {{ $session->session }} - OPENING DATE -
            {{ date_format(date_create($session->opening_date), 'D, d M Y') }}</strong> - CLOSING DATE -
            {{ date_format(date_create($session->closing_date), 'D, d M Y') }}</strong>
        </x-card>
    </x-section>
    <!-- /.section component -->
@endsection
