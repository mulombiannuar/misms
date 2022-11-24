@extends('layouts.app.form')

@section('content')
    <!-- Main content -->
    <x-section class="content">
        <x-card class="card-secondary" icon="fa-edit" :title="$title">
            <x-form.form action="{{ route('admin.sections.update', $section->section_id) }}" method="put"
                buttonName="Update Section" buttonIcon="fa-edit" buttonClass="btn-secondary">

                <x-form.input class="col-md-3 col-sm-12" label="Section numeric" type="text" name="section_numeric"
                    placeholder="Section numeric " value="{{ $section->form->form_numeric }}" readonly />

                <x-form.input class="col-md-3 col-sm-12" label="Section name" type="text" name="section_name"
                    placeholder="Section name e.g East" value="{{ $section->section_name }}" />

                <x-form.select class="col-md-6 col-sm-12" value="" label="Section teacher" name="section_teacher">
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">
                            {{ $user->name . '-' . $user->email }}</option>
                    @endforeach
                </x-form.select>
            </x-form.form>
        </x-card>
    </x-section>
    <!-- /.section component -->
@endsection
