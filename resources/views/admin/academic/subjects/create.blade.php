@extends('layouts.app.form')

@section('content')
    <!-- Main content -->
    <x-section class="content">
        <x-card class="card-secondary" icon="fa-plus" :title="$title">
            <x-form.form action="{{ route('admin.subjects.store') }}" method="post" buttonName="Save New Subject"
                buttonIcon="fa-plus" buttonClass="btn-secondary">

                <x-form.input class="col-md-6 col-sm-12" label="Subject name" type="text" name="subject_name"
                    placeholder="Subject name e.g Biology" value="" />

                <x-form.input class="col-md-6 col-sm-12" label="Subject code" type="number" name="subject_code"
                    placeholder="Subject code e.g 102" value="" />

                <x-form.input class="col-md-4 col-sm-12" label="Subject short" type="text" name="subject_short"
                    placeholder="Subject short form e.g BIO" value="" maxlength="3" minlength="3" />

                <x-form.select class="col-md-4 col-sm-12" value="" label="Subject optionality" name="optionality">
                    <option value="Optional">Optional</option>
                    <option value="Compulsory">Compulsory</option>
                </x-form.select>

                <x-form.select class="col-md-4 col-sm-12" value="" label="Subject group" name="group">
                    <option value="Mathematics">Mathematics</option>
                    <option value="Languages">Languages</option>
                    <option value="Sciences">Sciences</option>
                    <option value="Humanities">Humanities</option>
                    <option value="Technicals">Technicals</option>
                </x-form.select>
            </x-form.form>
        </x-card>
    </x-section>
    <!-- /.section component -->
@endsection
