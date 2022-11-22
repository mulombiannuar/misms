@extends('layouts.app.form')

@section('content')
    <!-- Main content -->
    <x-section class="content">
        <x-card class="card-secondary" icon="fa-edit" :title="$title">
            <x-form.form action="{{ route('admin.forms.update', $form->form_id) }}" method="put" buttonName="Update Class"
                buttonIcon="fa-edit" buttonClass="btn-secondary">

                <x-form.input class="col-md-6 col-sm-12" label="Class name" type="text" name="form_name"
                    placeholder="Class name e.g form one" value="{{ $form->form_name }}" />

                <x-form.input class="col-md-6 col-sm-12" label="Class numeric" type="number" name="form_numeric"
                    placeholder="Class name numeric" value="{{ $form->form_numeric }}" readonly />

                <x-form.input class="col-md-6 col-sm-12" label="Minimum subjects" type="number" name="min_subs"
                    placeholder="Enter minimum subjects" value="{{ $form->min_subs }}" pattern="/^-?\d+\.?\d*$/"
                    onkeypress="if(this.value.length==2) return false;" />

                <x-form.input class="col-md-6 col-sm-12" label="Maximum subjects" type="number" name="max_subs"
                    placeholder="Enter maximum subjects" value="{{ $form->max_subs }}" pattern="/^-?\d+\.?\d*$/"
                    onkeypress="if(this.value.length==2) return false;" />
            </x-form.form>
        </x-card>
    </x-section>
    <!-- /.section component -->
@endsection
