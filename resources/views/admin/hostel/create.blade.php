@extends('layouts.app.form')

@section('content')
    <!-- Main content -->
    <x-section class="content">
        <x-card class="card-secondary" icon="fa-plus" :title="$title">
            <x-form.form action="{{ route('hostel.hostels.store') }}" method="post" buttonName="Save New Hostel"
                buttonIcon="fa-plus" buttonClass="btn-secondary">

                <x-form.input class="col-md-6 col-sm-12" label="Hostel name" type="text" name="hostel_name"
                    placeholder="Hostel name e.g Jamhuri" value="" />

                <x-form.input class="col-md-6 col-sm-12" label="Hostel Capacity" type="number" name="hostel_capacity"
                    placeholder="Hostel Capacity" value="" />

                <x-form.input class="col-md-6 col-sm-12" label="Hostel Motto" type="text" name="hostel_motto"
                    placeholder="Hostel Motto" value="" />

                <x-form.select class="col-md-6 col-sm-12" value="" label="Hostel Master" name="hostel_master">
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
