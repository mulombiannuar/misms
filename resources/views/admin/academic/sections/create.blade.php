@extends('layouts.app.form')

@section('content')
    <!-- Main content -->
    <x-section class="content">
        <x-card class="card-secondary" icon="fa-plus" :title="$title">
            <x-form.form action="{{ route('admin.sections.store') }}" method="post" buttonName="Save New Section"
                buttonIcon="fa-plus" buttonClass="btn-secondary">

                <x-form.select class="col-md-3 col-sm-12" value="" label="Section numeric" name="section_numeric">
                    @foreach ($forms as $form)
                        <option value="{{ $form->form_numeric }}">
                            {{ $form->form_name }}</option>
                    @endforeach
                </x-form.select>

                <x-form.input class="col-md-3 col-sm-12" label="Section name" type="text" name="section_name"
                    placeholder="Section name e.g East" value="" />

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
