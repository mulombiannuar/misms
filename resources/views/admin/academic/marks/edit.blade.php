@extends('layouts.app.form')

@section('content')
    <!-- Main content -->
    <x-section class="content">
        <x-card class="card-primary" icon="fa-edit" :title="$title">
            <x-form.form action="{{ route('marks.scores.update', $score->score_id) }}" method="put"
                buttonName="Update Student Score" buttonIcon="fa-edit" buttonClass="btn-secondary">

                <x-form.input class="col-md-4 col-sm-12" label="Student name" type="text" name="name"
                    placeholder="Student name" value=" {{ $score->name }}" readonly />

                <x-form.input class="col-md-4 col-sm-12" label="Admission no" type="text" name="admission"
                    placeholder="Admission no" value=" {{ $score->admission_no }}" readonly />

                <x-form.input class="col-md-4 col-sm-12" label="Student score" type="number" name="score"
                    placeholder="Student score" value="{{ $score->score }}"
                    onKeyPress="if(this.value.length==2) return false;" minlength="2" maxlength="2" />
            </x-form.form>
        </x-card>
    </x-section>
    <!-- /.section component -->
@endsection
