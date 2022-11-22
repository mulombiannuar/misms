@extends('layouts.app.form')

@section('content')
    <!-- Main content -->
    <x-section class="content">
        <x-card class="card-secondary" icon="fa-calendar" :title="$title">
            <x-form.form action="{{ route('admin.forms.store') }}" method="post" buttonName="Save New Class"
                buttonIcon="fa-plus" buttonClass="btn-secondary">

                <x-form.input class="col-md-6 col-sm-12" label="Class name" type="text" name="form_name"
                    placeholder="Class name e.g form one" value="" />

                <x-form.select class="col-md-6 col-sm-12" value="" label="Class numeric" name="form_numeric">
                    @for ($i = 1; $i < 8; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </x-form.select>

                <x-form.input class="col-md-6 col-sm-12" label="Minimum subjects" type="number" name="min_subs"
                    placeholder="Enter minimum subjects" value="" pattern="/^-?\d+\.?\d*$/"
                    onkeypress="if(this.value.length==2) return false;" />

                <x-form.input class="col-md-6 col-sm-12" label="Maximum subjects" type="number" name="max_subs"
                    placeholder="Enter maximum subjects" value="" pattern="/^-?\d+\.?\d*$/"
                    onkeypress="if(this.value.length==2) return false;" />
            </x-form.form>
        </x-card>
    </x-section>
    <!-- /.section component -->
@endsection
