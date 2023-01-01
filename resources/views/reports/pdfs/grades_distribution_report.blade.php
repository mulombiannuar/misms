@extends('layouts.app.report')

@section('content')
    @foreach ($sections as $section)
        <main style="page-break-after: always;">
            <h6>SECTION PERFORMANCE / {{ strtoupper($section->section_numeric . $section->section_name) }} /
                {{ $exam->name }} /
                {{ $exam->year }}</h6>
            <div>
                Section {{ $title }}
            </div>
        </main>
    @endforeach
    <main>
        <h6>CLASS PERFORMANCE / {{ strtoupper($form->form_name) }} /
            {{ $exam->name }} /
            {{ $exam->year }}</h6>
        <div>
            Class {{ $title }}
        </div>
    </main>
@endsection
